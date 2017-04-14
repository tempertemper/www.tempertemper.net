<?php

    $API    = new PerchAPI(1.0, 'assets');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    if (!$CurrentUser->has_priv('assets.delete')) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/assets/');
    }

    $Assets  = new PerchAssets_Assets;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $assetID  = (int) $_GET['id'];
        
        $Asset = $Assets->find($assetID);
    }

    if (!$Asset || !is_object($Asset)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/assets/');
    }

    /* --------- Delete Form ----------- */
    
    $Form = new PerchForm('delete');
    
    if ($Form->posted() && $Form->validate()) {
        
        $Asset->delete();
        
        if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/apps/assets/';
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/assets/');
    	}
           	
    	
    }

