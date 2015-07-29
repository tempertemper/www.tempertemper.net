<?php

class PerchResources extends PerchFactory
{
	protected $singular_classname = 'PerchResource';
    protected $table    = 'resources';
    protected $pk   = 'resourceID';

    static $logged = array();
	
    public function log($app='content', $bucket='default', $file, $parentID=0, $resource_key='', $library_resource=false, $details=false, $AssetMeta=false)
    {
        if (isset($details['assetID'])) {
            $newID = $details['assetID'];
        }else{
            $data = array(
                'resourceApp'       => $app,
                'resourceBucket'    => $bucket,
                'resourceFile'      => $file,
                'resourceKey'       => $resource_key,
                'resourceParentID'  => $parentID,
                'resourceType'      => $this->_get_type($file),
                'resourceCreated'   => date('Y-m-d H:i:s'),
                'resourceInLibrary' => ($library_resource ? '1' : '0'),
                );

            if (PerchUtil::count($details)) {
                foreach($details as $key=>$val) {

                    switch ($key) {
                        case 'w':
                            $data['resourceWidth'] = $val;
                            break;
                        case 'h':
                            $data['resourceHeight'] = $val;
                            break;
                        case 'target_w':
                            $data['resourceTargetWidth'] = $val;
                            break;
                        case 'target_h':
                            $data['resourceTargetHeight'] = $val;
                            break;
                        case 'crop':
                            $data['resourceCrop'] = ($val ? '1' : '0');
                            break;
                        case 'density':
                            $data['resourceDensity'] = $val;
                            break;
                        case 'size':
                            $data['resourceFileSize'] = $val;
                            break;
                        case 'mime':
                            $data['resourceMimeType'] = $val;
                            break;
                        case 'title':
                            $data['resourceTitle'] = $val;
                            break;
                    }
                }
            }

            if (!isset($data['resourceTitle'])) {

                if ($AssetMeta) {
                    $data['resourceTitle'] = $AssetMeta->get_title();
                }else{
                    $data['resourceTitle'] = PerchUtil::filename(PerchUtil::strip_file_extension($data['resourceFile']), false);        
                }
                
            }
            
            $newID = $this->db->insert($this->table, $data, true);

            if ($newID=='0') {
                $sql = 'SELECT resourceID FROM '.$this->table.' WHERE resourceBucket='.$this->db->pdb($bucket).' AND resourceFile='.$this->db->pdb($file).' LIMIT 1';
                $newID = $this->db->get_value($sql);
            }

            // Tags
            if ($AssetMeta) {
                $tags = $AssetMeta->get_tags();
                if (PerchUtil::count($tags)) {
                    if (!class_exists('PerchAssets_Tags', false)) {
                        include_once(PERCH_CORE.'/apps/assets/PerchAssets_Tags.class.php');
                        include_once(PERCH_CORE.'/apps/assets/PerchAssets_Tag.class.php');
                    }
                    $Tags = new PerchAssets_Tags();
                    $Tags->assign_tag_array($newID, $tags, true);
                }
            }
        }

    	PerchResources::$logged[] = $newID;

    	return $newID;
    }

    public function get_logged_ids()
    {
    	$ids = PerchResources::$logged;

    	PerchResources::$logged = array();

    	return $ids;
    }

    public function log_extra_ids($ids) 
    {
        if (PerchUtil::count($ids)) {
            foreach($ids as $id) {
                PerchResources::$logged[] = $id;
            }
        }
    }

    public function get_not_in_subquery($app='content', $subquery)
    {
        #PerchUtil::mark('Resource clean up');
        $clean_resources = true;
        if (defined('PERCH_CLEAN_RESOURCES')) $clean_resources = PERCH_CLEAN_RESOURCES;

        if ($clean_resources==false) { 
            // If Perch is configured not to clean up resources, return no results so nothing gets deleted.
            return false;
        }

    	$sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'resources
                WHERE resourceApp='.$this->db->pdb($app).' 
                    AND resourceInLibrary=0 
                    AND resourceCreated<'.$this->db->pdb(date('Y-m-d H:i:s', strtotime('-24 HOURS'))).' 
                    AND resourceID NOT IN (SELECT resourceID FROM '.PERCH_DB_PREFIX.'resource_log)';
        $rows = $this->db->get_rows($sql);

        #PerchUtil::mark('/ Resource clean up');
        return $this->return_instances($rows);
    }

    public function mark_group_as_library($ids) 
    {
        if (PerchUtil::count($ids)) {
            foreach($ids as $id) {
                $sql = 'UPDATE '.$this->table.' SET resourceInLibrary=1 WHERE resourceID='.$this->db->pdb((int)$id);
                $this->db->execute($sql);
            }    
        }
        
    }

    /**
     * When something bad is going on, like the resource_log table was missing, we log everything we have to make sure nothing gets erroneously removed.
     */
    public function log_unlogged_resources_for_safety()
    {
        $sql = 'SELECT resourceID FROM '.$this->table.' WHERE resourceID NOT IN (SELECT resourceID FROM '.PERCH_DB_PREFIX.'resource_log)';
        $rows = $this->db->get_rows($sql);

        if (PerchUtil::count($rows)) {
            foreach($rows as $row) {
                $this->db->insert(PERCH_DB_PREFIX.'resource_log', array(
                    'appID'      => 'perch_core',
                    'itemFK'     => 'recovery',
                    'itemRowID'  => '1',
                    'resourceID' => $row['resourceID'],
                    ));
            }
        }
    }

    private function _get_type($file)
    {
    	return strtolower(substr(PerchUtil::file_extension($file), -4));
    }

}
