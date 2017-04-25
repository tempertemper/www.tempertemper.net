<?php
	
	PerchUI::set_subnav([
		[
			'page'   => 'core/settings', 
			'label'  => 'General'
		],
		[
			'page'   => [
							'core/settings/backup',
							'core/settings/backup/edit',
							'core/settings/backup/restore',
							'core/settings/backup/restore/general',
							'core/settings/backup/delete',
						], 
			'label'  => 'Backup', 
			'runway' => true, 
			'priv'   => 'perch.backups.manage'
		],
		[
			'page'   => 'core/settings/email', 
			'label'  => 'Email'
		],
		[
			'page'   => 'core/settings/tasks', 
			'label'  => 'Scheduled tasks'
		],
		[
			'page'   => [
							'core/settings/menu', 
							'core/settings/menu/items', 
							'core/settings/menu/edit', 
							'core/settings/menu/delete', 
							'core/settings/menu/reorder', 
							'core/settings/menu/section/edit', 
						],
			'label'  => 'Menu manager', 
			'runway' => true, 
			'priv'   => 'perch.menus.manage'
		],
		[
			'page'   => [
							'core/settings/diagnostics',
							'core/settings/diagnostics/add-ons',
							'core/settings/update',
						], 
			'label'  => 'Diagnostics'
		]
	]);