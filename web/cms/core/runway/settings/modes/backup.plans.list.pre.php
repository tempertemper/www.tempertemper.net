<?php
	$API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


	$Paging = new PerchPaging();
	$Paging->set_per_page(24);


	$Plans = new PerchBackupPlans;

	$plans = $Plans->all($Paging);

	if (!PerchUtil::count($plans)) {
		PerchUtil::redirect(PERCH_LOGINPATH.'/core/settings/backup/edit/');
	}

	$errors = false;

	$backup_config = PerchConfig::get('env');

	if (!isset($backup_config['temp_folder']) || !is_writable($backup_config['temp_folder'])) {
		$Alert->set('notice', PerchLang::get('Your backup temp folder is not set, or is not writable.'));
		$errors = true;
	}


