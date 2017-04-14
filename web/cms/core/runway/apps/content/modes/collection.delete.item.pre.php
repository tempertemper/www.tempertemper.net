<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $collection_id  = (int) $_GET['id'];
        $item_id    = (int) $_GET['itm'];
        
        $Collections  = new PerchContent_Collections;
        $Collection = $Collections->find($collection_id);
        
    }

    if (!$Collection || !is_object($Collection)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

    // App menu
    if ($Collection->collectionInAppMenu()) {
        $Perch = Perch::fetch();
        $Perch->set_section('collection:'.$Collection->collectionKey());    
    }

    // set the current user
    $Collection->set_current_user($CurrentUser->id());

    /* --------- Delete Form ----------- */
    
    $Form = $API->get('Form');
    $Form->set_name('delete');
    
    if ($Form->posted() && $Form->validate() && isset($item_id)) {
        
        $Collection->delete_item($item_id);
        
        if ($Form->submitted_via_ajax) {
            echo PERCH_LOGINPATH . '/core/apps/content/collections/?id='.$Collection->id();
            exit;
        }else{
            PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/collections/?id='.$Collection->id());
        }
            
        
    }
