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
     * @param  array  $filters       [description]
     * @return object                [description]
     */
   public function get_filtered_for_admin(PerchPaging $Paging, $filters, $CurrentUser, $template_buckets=false)
    {
        $sort_val = null;
        $sort_dir = null;

        list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();

        $sql = $Paging->select_sql();
        $sql .= ' r1.*
                FROM '.$this->table.' r1
                WHERE  r1.resourceKey=\'orig\' AND r1.resourceAWOL=0   ';

        if (PerchUtil::count($filters)) {
            foreach($filters as $filter=>$filter_value) {
                switch($filter) {

                    case 'bucket':
                        $filter_value = explode(' ', $filter_value);
                        $filter_value = $this->hydrate_bucket_list($filter_value, $CurrentUser);
                        $sql .= ' AND r1.resourceBucket IN ('.$this->db->implode_for_sql_in($filter_value). ') ';
                        break;

                    case 'app':
                        $sql .= ' AND r1.resourceApp='.$this->db->pdb($filter_value). ' ';
                        break;

                    case 'type':

                        $type_map = PerchAssets_Asset::get_type_map();
                        $neg = false;

                        // check for negative match
                        if (substr($filter_value, 0, 1)==='!') {
                            $neg = true;
                            $filter_value = substr($filter_value, 1);
                        }

                        if (array_key_exists($filter_value, $type_map)) {
                            $operator = ($neg ? 'NOT IN' : 'IN');
                            $sql .= ' AND r1.resourceType '.$operator.' ('.$this->db->implode_for_sql_in($type_map[$filter_value]['exts']).') ';
                        }else{
                            $operator = ($neg ? '!=' : '=');
                            $sql .= ' AND r1.resourceType '.$operator.' '.$this->db->pdb($filter_value). ' ';
                        }
                
                        break;

                    case 'date':
                        $ts = strtotime($filter_value);
                        $sql .= ' AND r1.resourceCreated BETWEEN '.$this->db->pdb(date('Y-m-d 00:00:00', $ts)). ' AND '.$this->db->pdb(date('Y-m-d 25:59:59', $ts)). ' ';
                        break;

                    case 'tag':
                        $sql .= ' AND r1.resourceID IN (
                                    SELECT r2t.resourceID FROM '.PERCH_DB_PREFIX.'resources_to_tags r2t, '.PERCH_DB_PREFIX.'resource_tags rt
                                    WHERE r2t.tagID=rt.tagID AND rt.tagSlug=' .$this->db->pdb($filter_value). '
                                    ) ';
                        break;

                }
            }
        }

        $sql .= ' AND r1.resourceBucket IN ('.$this->db->implode_for_sql_in($this->get_available_buckets($CurrentUser)).') ';

        if (PerchUtil::count($template_buckets)) {
            $template_buckets = $this->hydrate_bucket_list($template_buckets, $CurrentUser);
            $sql .= ' AND r1.resourceBucket IN ('.$this->db->implode_for_sql_in($template_buckets).') ';
        }

        if ($sort_val) {
            $sql .= ' ORDER BY r1.'.$sort_val.' '.$sort_dir.' ';
        } else {
            $sql .= ' ORDER BY r1.resourceUpdated DESC, r1.resourceID DESC ';
        }

        

        $sql .= $Paging->limit_sql();

        $rows = $this->db->get_rows($sql);

        $Paging->set_total($this->db->get_count($Paging->total_count_sql()));

        // load thumbs
        if (PerchUtil::count($rows)) {
            // we do this here because self-joining in the main query gets really slow really fast.
            // this generates a fixed number of very small, very fast queries that scale a lot better.
            foreach($rows as &$row) {
                $sql = 'SELECT resourceFile AS thumb, resourceWidth AS thumbWidth, resourceHeight AS thumbHeight, resourceDensity AS thumbDensity
                        FROM '.$this->table.' WHERE resourceParentID='.$this->db->pdb((int)$row['resourceID']).' AND resourceKey=\'thumb\' AND resourceAWOL!=1 LIMIT 1';
                $thumb_row = $this->db->get_row($sql);
                if (PerchUtil::count($thumb_row)) {
                    $row = array_merge($row, $thumb_row);
                }
            }
        }

        

        return $this->return_instances($rows);

    }


    public function old_get_filtered_for_admin(PerchPaging $Paging, $filters, $CurrentUser, $template_buckets=false)
    {
        $sort_val = null;
        $sort_dir = null;

        list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();

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
                        $filter_value = explode(' ', $filter_value);
                        $filter_value = $this->hydrate_bucket_list($filter_value, $CurrentUser);
                        $sql .= ' AND r1.resourceBucket IN ('.$this->db->implode_for_sql_in($filter_value). ') ';
                        break;

                    case 'app':
                        $sql .= ' AND r1.resourceApp='.$this->db->pdb($filter_value). ' ';
                        break;

                    case 'type':

                        $type_map = PerchAssets_Asset::get_type_map();
                        $neg = false;

                        // check for negative match
                        if (substr($filter_value, 0, 1)==='!') {
                            $neg = true;
                            $filter_value = substr($filter_value, 1);
                        }

                        if (array_key_exists($filter_value, $type_map)) {
                            $operator = ($neg ? 'NOT IN' : 'IN');
                            $sql .= ' AND r1.resourceType '.$operator.' ('.$this->db->implode_for_sql_in($type_map[$filter_value]['exts']).') ';
                        }else{
                            $operator = ($neg ? '!=' : '=');
                            $sql .= ' AND r1.resourceType '.$operator.' '.$this->db->pdb($filter_value). ' ';
                        }
                
                        break;

                    case 'date':
                        $ts = strtotime($filter_value);
                        $sql .= ' AND r1.resourceCreated BETWEEN '.$this->db->pdb(date('Y-m-d 00:00:00', $ts)). ' AND '.$this->db->pdb(date('Y-m-d 25:59:59', $ts)). ' ';
                        break;

                    case 'tag':
                        $sql .= ' AND r1.resourceID IN (
                                    SELECT r2t.resourceID FROM '.PERCH_DB_PREFIX.'resources_to_tags r2t, '.PERCH_DB_PREFIX.'resource_tags rt
                                    WHERE r2t.tagID=rt.tagID AND rt.tagSlug=' .$this->db->pdb($filter_value). '
                                    ) ';
                        break;

                }
            }
        }

        $sql .= ' AND r1.resourceBucket IN ('.$this->db->implode_for_sql_in($this->get_available_buckets($CurrentUser)).') ';

        if (PerchUtil::count($template_buckets)) {
            $template_buckets = $this->hydrate_bucket_list($template_buckets, $CurrentUser);
            $sql .= ' AND r1.resourceBucket IN ('.$this->db->implode_for_sql_in($template_buckets).') ';
        }

        if ($sort_val) {
            $sql .= ' ORDER BY r1.'.$sort_val.' '.$sort_dir.' ';
        } else {
            $sql .= ' ORDER BY r1.resourceUpdated DESC, r1.resourceID DESC ';
        }

		

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

    public function get_available_buckets($CurrentUser, $exclude_roles = array('backup'), $privs = array())
    {
        $list = ['default', $CurrentUser->roleSlug()];
    	$sql = 'SELECT DISTINCT resourceBucket FROM '.$this->table.' 
    			WHERE resourceAWOL=0 AND resourceType !=""';
    	$result = $this->db->get_rows_flat($sql);
        if (is_array($result)) {
            $list = array_merge($list, $result);
        }
        $list = array_unique($list);
        if (!$list) $list = array();

        $bucket_list_file = PerchUtil::file_path(PERCH_PATH.'/config/buckets.php');
        if (file_exists($bucket_list_file)) {
            $bucket_list = include ($bucket_list_file);
            if (PerchUtil::count($bucket_list)) {
                foreach($bucket_list as $key=>$val) {
                    if (!in_array($key, $list)) {
                        if (!isset($val['role']) || (isset($val['role']) && !in_array($val['role'], $exclude_roles))) {
                            $list[] = $key;
                        }  
                    } 
                }
            }
        }

        if ($list) {
            // filter for privs
            $list = array_filter($list, function($b) use ($CurrentUser, $privs) {
                return $CurrentUser->can_use_bucket($b, $privs);
            });
            sort($list);
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

    public function find_original($ids)
    {
        $sql = 'SELECT r1.*, r2.resourceFile AS thumb, r2.resourceWidth AS thumbWidth, r2.resourceHeight AS thumbHeight, r2.resourceDensity AS thumbDensity
                FROM '.$this->table.' r1
                    LEFT OUTER JOIN '.$this->table.' r2 ON r2.resourceParentID=r1.resourceID AND r2.resourceKey=\'thumb\'
                        AND r2.resourceAWOL!=1
                WHERE  r1.resourceKey=\'orig\' AND r1.resourceAWOL=0 
                    AND r1.resourceID IN ('.$this->db->implode_for_sql_in($ids).')
                ORDER BY r1.resourceID DESC 
                LIMIT 1';


        $row = $this->db->get_row($sql);
        return $this->return_instance($row);
    }

    public function search($term, $filters = array(), $CurrentUser = false)
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

                    case 'tag':
                        $ThisTag  = $Tags->get_one_by('tagSlug', $filter_value);
                        $filter_sql .= 'AND r.resourceID IN ( SELECT resourceID FROM '.PERCH_DB_PREFIX.'resources_to_tags WHERE tagID='.$this->db->pdb((int)$ThisTag->id()).') ';

                        break;
                }
            }
        }

        $filter_sql .= ' AND r.resourceBucket IN ('.$this->db->implode_for_sql_in($this->get_available_buckets($CurrentUser)).') ';

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

    public function hydrate_bucket_list($template_buckets, $CurrentUser)
    {
        if (!in_array('cms-role', $template_buckets)) {
            return $template_buckets;
        }

        if (!PERCH_RUNWAY) {
            return $template_buckets;
        }
        
        $new_list = [];

        foreach($template_buckets as $bucket) {
            $bucket = urldecode($bucket);
            if ($bucket == 'cms-role') {
                $sql     = 'SELECT bucket FROM '.PERCH_DB_PREFIX.'user_role_buckets WHERE roleID='.$this->db->pdb((int)$CurrentUser->roleID()).' AND roleDefault=1';
                $results = $this->db->get_rows($sql);
                if (PerchUtil::count($results)) {
                    foreach($results as $result) {
                        if (!in_array($result['bucket'], $new_list)) {
                            $new_list[] = $result['bucket'];    
                        }
                    }
                }
            } else {
                if (!in_array($bucket, $new_list)) {
                    $new_list[] = $bucket;
                }
            }
        }

        return $new_list;
    }

}
