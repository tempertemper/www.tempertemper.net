<?php
	echo PerchUtil::subnav($CurrentUser, array(
		array('page'=>'core/settings', 'label'=>'General'),
		array('page'=>array(
						'core/settings/backup',
						'core/settings/backup/edit',
						'core/settings/backup/restore',
						'core/settings/backup/restore/general',
						'core/settings/backup/delete',
						), 
				'label'=>'Backup', 'runway'=>true, 'priv'=>'perch.backups.manage'),
		array('page'=>'core/settings/email', 'label'=>'Email'),
		array('page'=>'core/settings/tasks', 'label'=>'Scheduled tasks'),
		array('page'=>'core/settings/diagnostics', 'label'=>'Diagnostics')
	));
?>