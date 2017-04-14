<?php
    $API    = new PerchAPI(1.0, 'categories');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    if (!$CurrentUser->has_priv('categories.delete')) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/categories/');
    }

    $Categories  = new PerchCategories_Categories;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $catID  = (int) $_GET['id'];
        
        $Category = $Categories->find($catID);
    }

    if (!$Category || !is_object($Category)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/categories/');
    }

    /* --------- Delete Form ----------- */
    
    $Form = new PerchForm('delete');
    
    if ($Form->posted() && $Form->validate()) {
        
        $Category->delete();
        
        if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/apps/categories/sets/?id='.$Category->setID();
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/categories/sets/?id='.$Category->setID());
    	}
           	
    	
    }

