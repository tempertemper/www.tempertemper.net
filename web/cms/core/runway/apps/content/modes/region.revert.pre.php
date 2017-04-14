<?php
    $API  = new PerchAPI(1.0, 'content');
    $HTML = $API->get('HTML');
    $Lang   = $API->get('Lang');
    $Paging   = $API->get('Paging');


    $Regions = new PerchContent_Regions;
    $Region  = false;

    // Find the region
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $rev = (int) $_GET['rev'];
        $Region = $Regions->find($id);
    }

    // Check we have a region
    if (!$Region || !is_object($Region)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

    // Check permissions
    if (!$Region->role_may_edit($CurrentUser)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/edit/denied/');
    }

    $Pages = new PerchContent_Pages;
    $Page = $Pages->find($Region->pageID());

    if (!is_object($Page)) {
        $Page = $Pages->get_mock_shared_page();
    }


    $Form = new PerchForm('confirm');
    
    if (!$Page || !is_object($Page)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content');
    }

    // Check permission to revert
    if (!$CurrentUser->has_priv('content.regions.revert')) {
        
        if ($Form->submitted_via_ajax) {
            echo PERCH_LOGINPATH . '/core/apps/content';
            exit;    
        }else{
            PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content');    
        }
                
        
    }



    /* --------- Delete Form ----------- */
    
   
    
    if ($Form->posted() && $Form->validate()) {

    	$Region->roll_back($rev);
    	
    	if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/apps/content/revisions/?id='.$Region->id();
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/revisions/?id='.$Region->id());
    	}
    	    	
    }

    

?>