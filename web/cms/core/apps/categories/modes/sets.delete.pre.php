<?php

    $API    = new PerchAPI(1.0, 'categories');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    if (!$CurrentUser->has_priv('categories.sets.delete')) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/categories/');
    }

    $Sets  = new PerchCategories_Sets;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $setID  = (int) $_GET['id'];
        
        $Set = $Sets->find($setID);
    }

    if (!$Set || !is_object($Set)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/categories/');
    }

    /* --------- Delete Form ----------- */
    
    $Form = $API->get('Form');
    $Form->set_name('delete');
    
    if ($Form->posted() && $Form->validate()) {
        
        $Set->delete();
        
        if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/apps/categories/';
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/categories/');
    	}
           	
    	
    }

