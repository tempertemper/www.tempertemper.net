<?php

	$Paging = new PerchPaging();
	$Paging->set_per_page(24);

	$API  = new PerchAPI(1.0, 'categories');
	$HTML = $API->get('HTML');

	$Lang   = $API->get('Lang');

	$Sets = new PerchCategories_Sets;
	$Categories = new PerchCategories_Categories;

	$sets = $Sets->all($Paging);

	
	if (!file_exists(PERCH_TEMPLATE_PATH.'/categories')) {
	    $Alert->set('notice', PerchLang::get('The category templates could not be found. Please copy them to %s', '<code>'.PERCH_TEMPLATE_PATH.'/categories'.'</code>'));
	}