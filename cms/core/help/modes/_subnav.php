<?php
	echo PerchUtil::subnav($CurrentUser, array(
		array('page'=>array(
				'core/help',
				'core/help/textile',
				'core/help/markdown'
			), 'label'=>'Help')
	));
?>