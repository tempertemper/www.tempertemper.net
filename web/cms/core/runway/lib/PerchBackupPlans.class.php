<?php

class PerchBackupPlans extends PerchFactory
{
	protected $singular_classname = 'PerchBackupPlan';
	protected $table              = 'backup_plans';
	protected $pk                 = 'planID';
	protected $namespace 		  = 'backup';
	protected $event_prefix       = 'backup_plan';
	
	protected $default_sort_column  = 'planCreated';  

	public $static_fields   = array('planID', 
									'planTitle', 
									'planCreated', 
									'planCreatedBy', 
									'planUpdated', 
									'planUpdatedBy', 
									'planActive', 
									'planRole', 
									'planFrequency', 
									'planBucket');

	public function create($data)
	{
		if (!isset($data['planActive'])) {
			$data['planActive'] = '0';
		}

		return parent::create($data);
	}


}