<?php

class PerchAPI_Base extends PerchBase
{
	protected $api = false;

	public function update($data)
	{
	    $r = $this->db->update($this->table, $data, $this->pk, (int) $this->details[$this->pk]);
	    $this->details = array_merge($this->details, $data);

	    $this->log_resources();
	    
	    return $r;
	}

	public function log_resources()
	{

		$Resources = new PerchResources();
		$resourceIDs = $Resources->get_logged_ids();

	    if (PerchUtil::count($resourceIDs) && $this->api) {

	    	$app_id = $this->api->app_id;

	    	
	    	$sql = 'DELETE FROM '.PERCH_DB_PREFIX.'resource_log WHERE appID='.$this->db->pdb($app_id).' AND itemFK='.$this->db->pdb($this->pk).' AND itemRowID='.$this->db->pdb((int)$this->id());
	    	$this->db->execute($sql);
	    	

			$sql    = 'INSERT IGNORE INTO '.PERCH_DB_PREFIX.'resource_log(`appID`, `itemFK`, `itemRowID`, `resourceID`) VALUES';      
			$vals   = array();
			
	        foreach($resourceIDs as $id) {
	            $vals[] = '('.$this->db->pdb($app_id).','.$this->db->pdb($this->pk).','.(int)$this->id().','.(int)$id.')';
	        }

	        $sql .= implode(',', $vals);

	        $this->db->execute($sql);
	    }
	}    

}
