<?php
	echo PerchUtil::subnav($CurrentUser, array(
		array('page'=>'core/settings', 'label'=>'General'),
		array('page'=>'core/settings/email', 'label'=>'Email'),
		array('page'=>'core/settings/tasks', 'label'=>'Scheduled tasks'),
		array('page'=>'core/settings/diagnostics', 'label'=>'Diagnostics')
	));
?>