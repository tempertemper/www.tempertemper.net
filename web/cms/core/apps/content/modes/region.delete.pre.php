<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Regions = new PerchContent_Regions;
        $Region = $Regions->find($id);
        
        $Pages  = new PerchContent_Pages;

        if (is_object($Region)) {
            if ($Region->regionPage()=='*') {
                $Page = $Pages->get_mock_shared_page();
            }else{
                $Page = $Pages->find($Region->pageID());
            }
            
        }

        
    }
    
    if (!$Region || !is_object($Region)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content');
    }

    // Check permission to delete
    if ($CurrentUser->has_priv('content.regions.delete') || ($CurrentUser->has_priv('content.pages.delete.own') && $Page->pageCreatorID()==$CurrentUser->id())) {
        // we're ok.
        
    }else{ 
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content');
    }



    /* --------- Delete Form ----------- */
    
    $Form = $API->get('Form');
    $Form->set_name('delete');
    
    if ($Form->posted() && $Form->validate()) {
    	$Region->delete();
    	
    	if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.$Page->id();
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/page/?id='.$Page->id());
    	}
    	    	
    }else{
        print_r($_POST);
        die();
    }

