<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $collectionID  = (int) $_GET['id'];
        
        $Collections  = new PerchContent_Collections;
        $Collection = $Collections->find($collectionID);
        
    }

    if (!$Collections || !is_object($Collections)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/manage/collections/');
    }

    /* --------- Delete Form ----------- */
    
    $Form = $API->get('Form');
    $Form->set_name('delete');
    
    if ($Form->posted() && $Form->validate()) {
        
        $Collection->delete();
        
        if ($Form->submitted_via_ajax) {
            echo PERCH_LOGINPATH . '/core/apps/content/manage/collections/';
            exit;
        }else{
            PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/manage/collections/');
        }
                    
    }
