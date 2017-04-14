<?php

    $API    = new PerchAPI(1.0, 'assets');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    
    $Tags  = new PerchAssets_Tags;
    
    $Form = new PerchForm('delete');
	
    $message = false;

    // Check permissions
    /*
    if (!$CurrentUser->has_priv('assets.tags.delete')) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/assets/tags/');
    }
    */
        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $tagID = (int) $_GET['id'];    
        $Tag = $Tags->find($tagID);
    }else{
        PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/assets/tags/');
    }
    

    
    if ($Form->posted() && $Form->validate()) {
    
		$Tag->delete();

        if ($Form->submitted_via_ajax) {
            echo PERCH_LOGINPATH . '/core/apps/assets/tags/';
            exit;
        }else{
            PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/assets/tags/');
        }

    }
    
    
    
    $details = $Tag->to_array();
