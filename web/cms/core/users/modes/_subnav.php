<?php
	PerchUI::set_subnav([
		[ 	
			'page' => ['core/users','core/users/edit','core/users/add','core/users/delete'],
			'label'=>'Add/Edit'
		],
		
		array('page'=>'core/users/roles,core/users/roles/edit,core/users/roles/delete,core/users/roles/actions,core/users/roles/buckets', 'label'=>'Roles')
	]);