<?php
	$Collections = new PerchContent_Collections();
	$Items       = new PerchContent_CollectionItems();
	$Regions     = new PerchContent_Regions();



	$Collection = false;


	$Form = new PerchForm('edit');

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