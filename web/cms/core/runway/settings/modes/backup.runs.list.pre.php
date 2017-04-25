<?php
	$Plans  = new PerchBackupPlans;
	$Runs   = new PerchBackupRuns;
	$Paging = new PerchPaging;

	$Paging->set_per_page(20);

	$planID    = (int) PerchUtil::get('id');
	$Plan      = $Plans->find($planID);

	$API  = new PerchAPI(1.0, 'core');
	$HTML = $API->get('HTML');

	$message = false;

	$Form = new PerchForm('backup');

	if ($Form->posted() && $Form->validate()) {

		$result = $Plan->run(true);

		if ($result['result']=='OK') {
			$Alert->set('success', PerchLang::get('The backup completed successfully.'));
		}
	}




	$runs = $Runs->get_for_plan($planID, $Paging);

