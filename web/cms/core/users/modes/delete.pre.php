<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $User = $Users->find($id);
    }

    // if (filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
    //         $id = (int) filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    //         $User = $Users->find($id);
    //     }
    
    if (!$User || !is_object($User)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/users');
    }
    


    /* --------- Delete User Form ----------- */

    $Form = $API->get('Form');
    $Form->set_name('delete');


    if ($Form->posted() && $Form->validate()) {

		$User->delete();
		
		if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/users/';
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/users/');
    	}

    }
    


    $details = $User->to_array();

