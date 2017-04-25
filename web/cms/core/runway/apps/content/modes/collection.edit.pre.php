<?php
	$Collections = new PerchContent_Collections();
	$Items       = new PerchContent_CollectionItems();
	$Regions     = new PerchContent_Regions();

	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');

	$Collection = false;


	$Form = $API->get('Form');

	if ($Form->posted() && $Form->validate()) {
		$postvars = array('collectionKey', 'collectionTemplate');
		$data = $Form->receive($postvars);

		if (!$Collection) {
			$data['collectionOptions'] = '';
			$Collection = $Collections->create($data);

			if ($Collection) {
				PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/collections/options/?id='.$Collection->id().'&created=true');
			}
		}

	}


	$details = false;