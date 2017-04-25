<?php

    $API    = new PerchAPI(1.0, 'content');
    $HTML   = $API->get('HTML');
    $Lang   = $API->get('Lang');
    $Paging = $API->get('Paging');

    $Regions = new PerchContent_Regions;
    $Items   = new PerchContent_Items;
    $Region  = false;
    
    // Find the region
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
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

    
    $Form = new PerchForm('reorder');
    
    if ($Form->posted() && $Form->validate()) {
        $items = $Form->find_items('item_');
        if (PerchUtil::count($items)) {
            foreach($items as $itemID=>$itemOrder) {
                $Item = $Items->find_item($Region->id(), $itemID, $Region->regionLatestRev());
                if (is_object($Item)) {
                    $data = array();
                    $data['itemOrder'] = (int) $itemOrder;
                    $Item->update($data);
                }
            }
            $Region->set_option('sortField', '');
            if (!$Region->has_draft()) {
                $Region->publish();    
                $Region->index();
            }
            $Alert->set('success', PerchLang::get('Item orders successfully updated.'));
            

            PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/edit/?id='.$Region->id());
        }
    }

    $items = $Region->get_items_for_editing();

	$cols = $Region->get_edit_columns();
