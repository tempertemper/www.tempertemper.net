<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $region_id  = (int) $_GET['id'];
        $item_id    = (int) $_GET['itm'];
        
        $Regions  = new PerchContent_Regions;
        $Region = $Regions->find($region_id);
        
        $Pages  = new PerchContent_Pages;
        $Page = $Pages->find($Region->pageID());
    }

    if (!$Region || !is_object($Region)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content');
    }


    // set the current user
    $Region->set_current_user($CurrentUser->id());


    /* --------- Delete Form ----------- */
    
    $Form = $API->get('Form');
    $Form->set_name('delete');
    
    if ($Form->posted() && $Form->validate() && isset($item_id)) {
        
        $Region->delete_item($item_id);
        $Region->index();
        
        if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.$Region->id();
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/edit/?id='.$Region->id());
    	}
        
    	
    	
    }

