<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $MenuItems = new PerchMenuItems;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $MenuItem = $MenuItems->find($id);
    }

    if (!$MenuItem || !is_object($MenuItem)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/settings/menu/');
    }

    /* --------- Delete MenuItem Form ----------- */

    $Form = $API->get('Form');
    $Form->set_name('delete');


    if ($Form->posted() && $Form->validate()) {

        $parentID = $MenuItem->parentID();

        if ($parentID == 0) {
            $url = '/core/settings/menu/';
        } else {
            $url = '/core/settings/menu/items/?id='.$parentID;
        }

		$MenuItem->delete();
		
		if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . $url;
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . $url);
    	}

    }
    


    $details = $MenuItem->to_array();

