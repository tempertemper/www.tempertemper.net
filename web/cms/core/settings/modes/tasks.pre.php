<?php

	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(20);

	$ScheduledTasks = new PerchScheduledTasks();
	$tasks = $ScheduledTasks->get_recent($Paging);

	$apps = $Perch->get_apps();

	$app_lookup = array();

	if (PerchUtil::count($apps)) {
		foreach($apps as $app) {
			$app_lookup[$app['id']] = $app['label'];
		}	
	}
	
   