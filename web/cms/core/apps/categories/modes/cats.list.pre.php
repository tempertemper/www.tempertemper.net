<?php
	
	$API = new PerchAPI('categories', 1.0);
	$HTML = $API->get('HTML');

	$Sets 		= new PerchCategories_Sets;
	$Categories = new PerchCategories_Categories;

	if (isset($_GET['id']) && $_GET['id']!='') {
		$setID = (int) $_GET['id'];
		$Set = $Sets->find($setID);
	}else{
		$setID = false;
		$Set   = false;
	}

	if (!$setID) PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/categories/');

	$cats = $Categories->get_tree($setID);