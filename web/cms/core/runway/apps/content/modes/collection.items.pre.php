<?php
    $API  = new PerchAPI(1.0, 'content');
    $HTML = $API->get('HTML');
    $Lang   = $API->get('Lang');


    $Paging = new PerchPaging;
    $Paging->set_per_page(20);

    $Form = new PerchForm('add');
    
    if (($Form->posted() && $Form->validate()) || PerchUtil::get('add')=='1') {
        $Item = $Collection->add_new_item();   
        if (is_object($Item)) {
            PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/collections/edit/?id='.$Collection->id().'&itm='.$Item->itemID());
        }
    }

    $items = $Collection->get_items_for_editing(false, $Paging);

	if (!PerchUtil::count($items)) {


        if (PerchRequest::get('sort')) {

            // sort went wrong. 

            PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/collections/?id='.$Collection->id());

        } else {

            // No items(!) so add a new one and edit it.
                
            $Item = $Collection->add_new_item();   
            if (is_object($Item)) {
                PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/collections/edit/?id='.$Collection->id().'&itm='.$Item->itemID());
            }

        }


		
	}

    $cols = $Collection->get_edit_columns();
