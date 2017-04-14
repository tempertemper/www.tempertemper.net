<?php
    
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    $Collections = new PerchContent_Collections;
    $Items = new PerchContent_CollectionItems;
    $Collection  = false;

    // Find the region
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Collection = $Collections->find($id);
    }

    if (isset($_GET['itm']) && is_numeric($_GET['itm'])) {
        $item_id = (int) $_GET['itm'];
        $Item = $Items->find_item($Collection->id(), $item_id);
        $details    = $Collection->get_items_for_editing($item_id);
        if (PerchUtil::count($details)) {
            $details = PerchContent_Util::flatten_details($details);
        }
    }

    if (!$Collection || !is_object($Collection) || !$Item) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

    // App menu
    if ($Collection->collectionInAppMenu()) {
        $Perch = Perch::fetch();
        $Perch->set_section('collection:'.$Collection->collectionKey());    
    }
            
    // set the current user
    $Collection->set_current_user($CurrentUser->id());
    
    /* --------- Options Form ----------- */
    

    $Form = $API->get('Form');
    
    if ($Form->posted() && $Form->validate()) {
        $postvars = array('itemSearchable');
    	$data = $Form->receive($postvars);

        
        if (!isset($data['itemSearchable'])) {
            $data['itemSearchable'] = 0;
        }
 	   	    	
        $Item->update_revision($data);
        
                
        $Alert->set('success', PerchLang::get('Successfully updated'));
        
    }

    $options = $Items->find_item_revision($item_id);