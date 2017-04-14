<?php
	
	include(__DIR__.'/settings/inc/backup.php');
	include(__DIR__.'/lib/vendor/autoload.php');

	schedule_backups();
	$out[] = 'Backup';

	function schedule_backups()
	{
		$Plans = new PerchBackupPlans;
		$plans = $Plans->get_by('planActive', '1');
		if (PerchUtil::count($plans)) {
			foreach($plans as $Plan) {
				PerchScheduledTasks::register_task('Backup', 'plan_'.$Plan->id(), 10, 'scheduled_runway_backup');
			}
		}



	}
	
	function scheduled_runway_backup($last_run, $key)
	{
		$Plans = new PerchBackupPlans;
		$Plan  = $Plans->find(str_replace('plan_', '', $key));
		if ($Plan) {
			$result = $Plan->run();
		}

		if ($result) return $result;

		return [
			'result' => 'OK',
			'message' => 'Nothing to do.'
		];
	}
