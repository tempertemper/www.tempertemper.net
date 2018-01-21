<?php

	$Paging = new PerchPaging();
	$Paging->set_per_page(30);

	$API  = new PerchAPI(1.0, 'assets');
	$HTML = $API->get('HTML');
	$Lang = $API->get('Lang');

	$Assets = new PerchAssets_Assets;

	$view 		  = 'grid';
	$term 		  = false;

	$filters = array();
	    
	if (isset($_GET['filter']) && $_GET['filter']=='new') {
	    $filters['new'] = true;
	}
	
	if (isset($_GET['app']) && $_GET['app']!='') {
	    $filters['app'] = $_GET['app'];
	}

	if (isset($_GET['type']) && $_GET['type']!='') {
	    $filters['type'] = $_GET['type'];
	}

	if (isset($_GET['bucket']) && $_GET['bucket']!='') {
	    $filters['bucket'] = $_GET['bucket'];
	}

	if (isset($_GET['date']) && $_GET['date']!='') {
	    $filters['date'] = $_GET['date'];
	}

	if (isset($_GET['tag']) && $_GET['tag']!='') {
	    $filters['tag'] = $_GET['tag'];
	}


	if (isset($_GET['view']) && $_GET['view']!='') {
		if ($_GET['view']=='list') {
			$view = 'list';
		}else{
			$view = 'grid';
		}
	}

	if (isset($_GET['q']) && $_GET['q']!='') {
	    $term = $_GET['q'];
	    
	    $assets = $Assets->search($term, $filters, $CurrentUser);
	}else{
	    $assets = $Assets->get_filtered_for_admin($Paging, $filters, $CurrentUser); 
	}

	
