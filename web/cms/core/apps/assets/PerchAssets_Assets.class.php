<?php

class PerchAssets_Assets extends PerchFactory
{
    protected $singular_classname = 'PerchAssets_Asset';
    protected $table    = 'resources';
    protected $pk   = 'resourceID';

    protected $default_sort_column  = 'resourceUpdated DESC';  


    /**
     * Get a paginated list of assets for admin display, filtered by smartbar options
     * @param  boolean $Paging       [description]
     * @param  string  $filter       [description]
     * @param  string  $filter_value [description]
     * @return [type]                [description]
     */
    public function get_filtered_for_admin(PerchPaging $Paging, $filters)
    {
    	$sql = $Paging->select_sql();
    	$sql .= ' r1.*, r2.resourceFile AS thumb, r2.resourceWidth AS thumbWidth, r2.resourceHeight AS thumbHeight, r2.resourceDensity AS thumbDensity
				FROM '.$this->table.' r1
					LEFT OUTER JOIN '.$this->table.' r2 ON r2.resourceParentID=r1.resourceID AND r2.resourceKey=\'thumb\'
						AND r2.resourceAWOL!=1
				WHERE  r1.resourceKey=\'orig\' AND r1.resourceAWOL=0   ';

        if (PerchUtil::count($filters)) {
            foreach($filters as $filter=>$filter_value) {
                switch($filter) {

                    case 'bucket':
                        $sql .= ' AND r1.resourceBucket='.$this->db->pdb($filter_value). ' ';
                        break;

                    case 'app':
                        $sql .= ' AND r1.resourceApp='.$this->db->pdb($filter_value). ' ';
                        break;

                    case 'type':

                        $type_map = PerchAssets_Asset::get_type_map();

                        if (array_key_exists($filter_value, $type_map)) {
                            $sql .= ' AND r1.resourceType IN ('.$this->db->implode_for_sql_in($type_map[$filter_value]['exts']).') ';
                        }else{
                            $sql .= ' AND r1.resourceType='.$this->db->pdb($filter_value). ' ';
                        }
                
                        break;

                    case 'date':
                        $ts = strtotime($filter_value);
                        $sql .= ' AND r1.resourceCreated BETWEEN '.$this->db->pdb(date('Y-m-d 00:00:00', $ts)). ' AND '.$this->db->pdb(date('Y-m-d 25:59:59', $ts)). ' ';
                        break;


                }
            }
        }



		$sql .= ' ORDER BY r1.resourceUpdated DESC, r1.resourceID DESC ';


		$sql .= $Paging->limit_sql();

		$rows = $this->db->get_rows($sql);

		$Paging->set_total($this->db->get_count($Paging->total_count_sql()));

		return $this->return_instances($rows);

    }


    public function get_available_types()
    {
    	$sql = 'SELECT DISTINCT resourceType FROM '.$this->table.' 
    			WHERE resourceAWOL=0 AND resourceType !="" ORDER BY resourceType ASC';
    	return $this->db->get_rows_flat($sql);
    }

    public function get_available_buckets()
    {
    	$sql = 'SELECT DISTINCT resourceBucket FROM '.$this->table.' 
    			WHERE resourceAWOL=0 AND resourceType !="" ORDER BY resourceType ASC';
    	$list = $this->db->get_rows_flat($sql);
        if (!$list) $list = array();

        $bucket_list_file = PerchUtil::file_path(PERCH_PATH.'/config/buckets.php');
        if (file_exists($bucket_list_file)) {
            $bucket_list = include ($bucket_list_file);
            if (PerchUtil::count($bucket_list)) {
                foreach($bucket_list as $key=>$val) {
                    if (!in_array($key, $list)) $list[] = $key;
                }
            }
        }

        return $list;
    }

    public function reindex()
    {
    	$sql = 'SELECT * FROM '.$this->table.' ORDER BY resourceUpdated ASC';
    	$rows = $this->db->get_rows($sql);

    	if (PerchUtil::count($rows)) {
    		foreach($rows as $row) {
    			$Asset = $this->return_instance($row);
    			$Asset->reindex();
    			unset($Asset);
    		}
    	}
    }

    public function get_resize_profile($assetID, $w, $h, $crop=false, $suffix=false, $density=1)
    {
        $out = false;

        $sql = 'SELECT * FROM '.$this->table.' 
                    WHERE resourceAWOL=0 
                        AND resourceParentID='.$this->db->pdb($assetID);

        if ($suffix) {
            $sql .= ' AND resourceKey='.$this->db->pdb($suffix);
        }else{
            $sql .= ' AND resourceTargetWidth='.$this->db->pdb((int)$w).'
                      AND resourceTargetHeight='.$this->db->pdb((int)$h).'
                      AND resourceCrop='.($crop ? 1 : 0).' 
                      AND resourceDensity='.$this->db->pdb($density);
        }

        $row = $this->db->get_row($sql);

        if ($row) {
            //PerchUtil::debug('Asset found! '. $row['resourceTitle']);
            $Asset = $this->return_instance($row);

            $out = array();
            $out['w']           = $Asset->resourceWidth();
            $out['h']           = $Asset->resourceHeight();
            $out['file_path']   = $Asset->file_path();
            $out['file_name']   = $Asset->resourceFile();
            $out['web_path']    = $Asset->web_path();
            $out['density']     = $Asset->resourceDensity();
            //$out['mime']      = 'image/JPEG';
            $out['_resourceID'] = $Asset->resourceID();
        }


        
        return $out;   
    }

    public function get_thumb($assetID)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE resourceParentID='.$this->db->pdb($assetID).' AND resourceKey='.$this->db->pdb('thumb');
        $row = $this->db->get_row($sql);
        return $this->return_instance($row);

    }

    public function search($term, $filters)
    {
        $term = trim($term);
        $tag  = PerchUtil::urlify($term);
        $Tags = new PerchAssets_Tags();
        $Tag  = $Tags->get_one_by('tagSlug', $tag);

        $sql = 'SELECT * FROM (';
        $filter_sql = '';

        if (PerchUtil::count($filters)) {
            foreach($filters as $filter=>$filter_value) {
                switch($filter) {

                    case 'bucket':
                        $filter_sql .= ' AND r.resourceBucket='.$this->db->pdb($filter_value). ' ';
                        break;

                    case 'app':
                        $filter_sql .= ' AND r.resourceApp='.$this->db->pdb($filter_value). ' ';
                        break;

                    case 'type':

                        $type_map = PerchAssets_Asset::get_type_map();

                        if (array_key_exists($filter_value, $type_map)) {
                            $filter_sql .= ' AND r.resourceType IN ('.$this->db->implode_for_sql_in($type_map[$filter_value]['exts']).') ';
                        }else{
                            $filter_sql .= ' AND r.resourceType='.$this->db->pdb($filter_value). ' ';
                        }
                
                        break;

                    case 'date':
                        $ts = strtotime($filter_value);
                        $filter_sql .= ' AND r.resourceCreated BETWEEN '.$this->db->pdb(date('Y-m-d 00:00:00', $ts)). ' AND '.$this->db->pdb(date('Y-m-d 25:59:59', $ts)). ' ';
                        break;
                }
            }
        }


        if ($Tag) {

            $sql .= 'SELECT r.*, 0.5 AS score, r2.resourceFile AS thumb, r2.resourceWidth AS thumbWidth, r2.resourceHeight AS thumbHeight, r2.resourceDensity AS thumbDensity 
                    FROM '.PERCH_DB_PREFIX.'resources r LEFT OUTER JOIN '.PERCH_DB_PREFIX.'resources r2 ON r2.resourceParentID=r.resourceID AND r2.resourceKey=\'thumb\'
                    AND r2.resourceAWOL!=1 JOIN '.PERCH_DB_PREFIX.'resources_to_tags r2t ON r.resourceID=r2t.resourceID AND r2t.tagID='.$Tag->id().'
                    WHERE  r.resourceAWOL=0 AND r.resourceKey=\'orig\' '.$filter_sql.'
                    UNION ALL ';

        }

        $sql .= 'SELECT r.*, MATCH(r.resourceTitle) AGAINST('.$this->db->pdb($term).') AS score, r2.resourceFile AS thumb, r2.resourceWidth AS thumbWidth, r2.resourceHeight AS thumbHeight, r2.resourceDensity AS thumbDensity 
                FROM '.PERCH_DB_PREFIX.'resources r LEFT OUTER JOIN '.PERCH_DB_PREFIX.'resources r2 ON r2.resourceParentID=r.resourceID AND r2.resourceKey=\'thumb\'
                AND r2.resourceAWOL!=1
                WHERE MATCH(r.resourceTitle) AGAINST('.$this->db->pdb($term).') AND r.resourceKey=\'orig\' '.$filter_sql.'

                ORDER BY score DESC, resourceUpdated DESC';


        $sql .= ') AS t GROUP BY resourceID';

        return $this->return_instances($this->db->get_rows($sql));
       
    }


    public function import_from_perch_gallery()
    {
        $sql = 'SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'gallery_images';
        $count = $this->db->get_count($sql);

        if ($count) {

            $Resources = new PerchResources;

            $sql = "SELECT i.imageID, i.imageBucket AS bucket, i.albumID, iv.versionPath AS file, i.imageAlt AS title, iv.versionWidth AS width, iv.versionHeight AS height
                    FROM `".PERCH_DB_PREFIX."gallery_image_versions` iv, `".PERCH_DB_PREFIX."gallery_images` i
                    WHERE iv.imageID=i.imageID AND iv.versionKey='original' AND i.imageStatus='active'";
            $originals = $this->db->get_rows($sql);

            if (PerchUtil::count($originals)) {
                foreach($originals as $orig) {

                    $sql = 'SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'resources WHERE resourceFile='.$this->db->pdb($orig['file']).' AND resourceBucket='.$this->db->pdb($orig['bucket']).' AND resourceKey='.$this->db->pdb('orig');
                    if ($this->db->get_count($sql)) {
                        continue;
                    }

                    $parentID = $Resources->log('perch_gallery', $orig['bucket'], $orig['file'], 0, 'orig', false, array(
                        'w'=>$orig['width'],
                        'h'=>$orig['height'],
                        'title'=>$orig['title'],
                        ));

                    if ($parentID) {

                        $this->db->insert(PERCH_DB_PREFIX.'resource_log', array(
                            'appID'      => 'perch_gallery',
                            'itemFK'     => 'albumID',
                            'itemRowID'  => $orig['albumID'],
                            'resourceID' => $parentID,
                            ), true);


                        $sql = "SELECT i.imageID, i.imageBucket AS bucket, i.albumID, iv.versionPath AS file, i.imageAlt AS title, iv.versionWidth AS width, iv.versionHeight AS height
                                FROM `".PERCH_DB_PREFIX."gallery_image_versions` iv, `".PERCH_DB_PREFIX."gallery_images` i
                                WHERE iv.imageID=i.imageID AND iv.versionKey='admin_thumb' AND i.imageStatus='active' AND i.imageID=".$this->db->pdb($orig['imageID']).' LIMIT 1';
                        $thumb = $this->db->get_row($sql);

                        if ($thumb) {
                            $thumbID = $Resources->log('perch_gallery', $thumb['bucket'], $thumb['file'], $parentID, 'thumb', false, array(
                                'w'=>$thumb['width'],
                                'h'=>$thumb['height'],
                                'target_w'=>'150',
                                'target_h'=>'150',
                                'title'=>$thumb['title'],
                                ));
                        }
                    }

                }
            }

        } 
    }

    public function mark_children_as_library($assetID)
    {
        $sql = 'UPDATE '.$this->table.' SET resourceInLibrary="1" WHERE resourceParentID='.$this->db->pdb($assetID);
        $this->db->execute($sql);
    }


    public function get_meta_data($file_path, $name)
    {
        if (!class_exists('PerchAssets_MetaData')) {
            include_once(__DIR__.'/PerchAssets_MetaData.class.php');    
        }
        
        $MetaData = new PerchAssets_MetaData();

        if (is_callable('iptcparse') && is_callable('getimagesize')) {
            $info = array();
            getimagesize($file_path); // once
            getimagesize($file_path, $info); // twice for luck (aka bugs);
            if(isset($info['APP13'])) {
                $iptc = iptcparse($info['APP13']);
                $MetaData->store_iptc($iptc);
            }
        }

        if (!$MetaData->get_title()) {
            $title = PerchUtil::filename(PerchUtil::strip_file_extension($name), false);
            $MetaData->set_title($title);
        }

        return $MetaData;
    }

}
