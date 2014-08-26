<?php

	$ScheduledTasks = new PerchScheduledTasks();
	$tasks = $ScheduledTasks->get_recent(20);

	$apps = $Perch->get_apps();

	$app_lookup = array();

	if (PerchUtil::count($apps)) {
		foreach($apps as $app) {
			$app_lookup[$app['id']] = $app['label'];
		}	
	}
	
    

?>