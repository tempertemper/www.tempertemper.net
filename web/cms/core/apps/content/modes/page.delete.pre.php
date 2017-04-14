<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];

        $Pages  = new PerchContent_Pages;
        $Page = $Pages->find($id);
    }

    $Form = $API->get('Form');
    $Form->set_name('delete');
    
    if (!$Page || !is_object($Page)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content');
    }

    // Check permission to delete
    if (!$CurrentUser->has_priv('content.pages.delete')) {

        if ($CurrentUser->has_priv('content.pages.delete.own') && $Page->pageCreatorID()==$CurrentUser->id()) {
            // ok - they can delete their own pages
        }else{
        
            if ($Form->submitted_via_ajax) {
                echo PERCH_LOGINPATH . '/core/apps/content';
                exit;    
            }else{
                PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content');    
            }
                    
        }
        
    }



    /* --------- Delete Form ----------- */
    
    
    
    if ($Form->posted() && $Form->validate()) {
    	$Page->delete();
    	
    	if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/apps/content/';
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    	}
    	    	
    }

    
