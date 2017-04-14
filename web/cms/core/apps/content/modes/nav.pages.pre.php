<?php

	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(1000);
    
    $NavGroups  = new PerchContent_NavGroups;
    $Pages      = new PerchContent_Pages;

        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $groupID = (int) $_GET['id'];    
        $NavGroup = $NavGroups->find($groupID);
    }else{
        $groupID = false;
        $NavGroup = false;
    }
    
