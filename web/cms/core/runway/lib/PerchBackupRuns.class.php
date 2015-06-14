<?php

class PerchBackupRuns extends PerchFactory
{
	protected $singular_classname = 'PerchBackupRun';
	protected $table              = 'backup_runs';
	protected $pk                 = 'runID';
	protected $namespace 		  = 'backup';
	protected $event_prefix       = 'backup_run';
	
	protected $default_sort_column  = 'runDateTime';  

	public $static_fields   = array('runID', 'planID', 'runDateTime', 'runResult', 'runMessage');

	public function get_last_run($planID)
	{
		$sql = 'SELECT * FROM '.$this->table.'
				WHERE planID='.$this->db->pdb($planID).'
				ORDER BY runDateTime DESC
				LIMIT 1';
		return $this->return_instance($this->db->get_row($sql));
	}

	public function get_last_database_run($planID)
	{
		$sql = 'SELECT * FROM '.$this->table.'
				WHERE planID='.$this->db->pdb($planID).'
					AND runType=\'db\'
				ORDER BY runDateTime DESC
				LIMIT 1';
		return $this->return_instance($this->db->get_row($sql));
	}

	public function get_for_plan($planID, $Paging)
	{
		if ($Paging && $Paging->enabled()) {
		    $sql = $Paging->select_sql();
		}else{
		    $sql = 'SELECT';
		}
		
		$sql .= ' * 
		        FROM ' . $this->table. ' 
		        WHERE planID='.$this->db->pdb($planID).'
		        ORDER BY runDateTime DESC';
		
		
		if ($Paging && $Paging->enabled()) {
		    $sql .=  ' '.$Paging->limit_sql();
		}
		
		$results = $this->db->get_rows($sql);
		
		if ($Paging && $Paging->enabled()) {
		    $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
		}
		
		return $this->return_instances($results);
	}

	public function restore_from_file($Bucket, $file)
	{
		$Run = new PerchBackupRun([
			'runDbFile' => $file
			]);
		return $Run->restore($Bucket);
	}

}