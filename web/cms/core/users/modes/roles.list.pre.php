<?php

	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(20);

    $Roles = new PerchUserRoles();
    $roles = $Roles->all($Paging);
