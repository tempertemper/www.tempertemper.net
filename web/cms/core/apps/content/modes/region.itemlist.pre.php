<?php
    $API    = new PerchAPI(1.0, 'content');
    $HTML   = $API->get('HTML');
    $Lang   = $API->get('Lang');
    $Paging = $API->get('Paging');


    $Pages = new PerchContent_Pages;
    $Page = $Pages->find((int)$Region->pageID());

    if (!is_object($Page)) {
        $Page = $Pages->get_mock_shared_page();
    }

    $Form = new PerchForm('add');

    if ($Form->posted() && $Form->validate()) {
        $Item = $Region->add_new_item();
        if (is_object($Item)) {
            PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/edit/?id='.$Region->id().'&itm='.$Item->itemID());
        }
    }

    $items = $Region->get_items_for_editing(false, $Paging);

	if (!PerchUtil::count($items)) {
		// No items(!) so add a new one and edit it.
		$Item = $Region->add_new_item();
        if (is_object($Item)) {
            PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/edit/?id='.$Region->id().'&itm='.$Item->itemID());
        }
	}

    $cols = $Region->get_edit_columns();
