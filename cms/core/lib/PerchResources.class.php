<?php

class PerchResources extends PerchFactory
{
	protected $singular_classname = 'PerchResource';
    protected $table    = 'resources';
    protected $pk   = 'resourceID';

    static $logged = array();
	
    public function log($app='content', $bucket='default', $file, $parentID=0, $resource_key='', $library_resource=false, $details=false)
    {
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
            $data['resourceTitle'] = PerchUtil::filename(PerchUtil::strip_file_extension($data['resourceFile']), false);    
        }
        
    	$newID = $this->db->insert($this->table, $data, true);

    	if ($newID=='0') {
    		$sql = 'SELECT resourceID FROM '.$this->table.' WHERE resourceBucket='.$this->db->pdb($bucket).' AND resourceFile='.$this->db->pdb($file).' LIMIT 1';
    		$newID = $this->db->get_value($sql);
    	}

        //PerchUtil::debug('Logging resource '.$resource_key.' '. $data['resourceTitle']. ' '.$newID);

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
    	$sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'resources
                WHERE resourceApp='.$this->db->pdb($app).' 
                    AND resourceInLibrary=0 
                    AND resourceCreated<'.$this->db->pdb(date('Y-m-d H:i:s', strtotime('-24 HOURS'))).' 
                    AND resourceID NOT IN (SELECT resourceID FROM '.PERCH_DB_PREFIX.'resource_log)';
        $rows = $this->db->get_rows($sql);

        return $this->return_instances($rows);
    }

    public function mark_group_as_library($ids) 
    {
        if (PerchUtil::count($ids)) {
            foreach($ids as $id) {
                $sql = 'UPDATE '.$this->table.' SET resourceInLibrary=1 WHERE resourceID='.$this->db->pdb($id);
                $this->db->execute($sql);
            }    
        }
        
    }

    private function _get_type($file)
    {
    	return strtolower(substr(PerchUtil::file_extension($file), -4));
    }

}
