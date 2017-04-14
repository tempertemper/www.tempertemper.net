<?php
	
	$API  = new PerchAPI(1.0, 'categories');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');
	
	$Sets 		= new PerchCategories_Sets;
	$Categories = new PerchCategories_Categories;

	$setID = false;
	$Set   = false;

	if (isset($_GET['id']) && $_GET['id']!='') {
		$setID = (int) $_GET['id'];
		$Set = $Sets->find($setID);
	}

	if ($setID==false) {
		PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/categories/');
	}

	$cats = $Categories->get_tree($setID);