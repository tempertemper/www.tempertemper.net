<?php

	$Paging = new PerchPaging();
	$Paging->set_per_page(24);

	$API  = new PerchAPI(1.0, 'backup');
	$HTML = $API->get('HTML');

	$Plans = new PerchBackupPlans;

	$plans = $Plans->all($Paging);

	$errors = false;


	$backup_config = PerchConfig::get('env');

	if (!isset($backup_config['temp_folder']) || !is_writable($backup_config['temp_folder'])) {
		$Alert->set('notice', PerchLang::get('Your backup temp folder is not set, or is not writable.'));
		$errors = true;
	}


