<?php
	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(24);

	$MenuItems = new PerchMenuItems;

	$Section = $MenuItems->find(PerchUtil::get('id'));

	$items = $MenuItems->get_for_parent(PerchUtil::get('id'));


