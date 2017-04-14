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
    
    // Check we have a region
    if (!$Collection || !is_object($Collection)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }
    
    // Check permissions
    if (!$Collection->role_may_edit($CurrentUser)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/edit/denied/');
    }

    // App menu
    if ($Collection->collectionInAppMenu()) {
        $Perch = Perch::fetch();
        $Perch->set_section('collection:'.$Collection->collectionKey());    
    }
    
    $Form = $API->get('Form');
    
    if ($Form->posted() && $Form->validate()) {
        $items = $Form->find_items('item_');
        if (PerchUtil::count($items)) {
            foreach($items as $itemID=>$itemOrder) {
                $Item = $Items->find_item_revision($itemID);
                if (is_object($Item)) {
                    $data = array();
                    $data['itemOrder'] = (int) $itemOrder;
                    $Item->update_revision($data);
                    $Item->index();
                }
            }
            $Collection->set_option('sortField', '');
            // if (!$Collection->has_draft()) {
            //     $Collection->publish();    
            //     $Collection->index();
            // }
            $Alert->set('success', PerchLang::get('Item orders successfully updated.'));
        }
    }

    $items = $Collection->get_items_for_editing();

	$cols = $Collection->get_edit_columns();
