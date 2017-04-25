<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $Roles = new PerchUserRoles;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Role = $Roles->find($id);
    }else{
        $id = false;
        $Role = false;
    }
    
    if (!$Role || !is_object($Role)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/users');
    }
    


    /* --------- Delete User Form ----------- */

    $Form = $API->get('Form');
    $Form->set_name('delete');


    if ($Form->posted() && $Form->validate()) {

        $postvars 	= array('roleID');
		$data = $Form->receive($postvars);
		
		$Role->migrate_users($data['roleID']); 

		$Role->delete();
		
		if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/users/roles/';
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/users/roles/');
    	}

    }
    


    $details = $Role->to_array();

    $all_roles = $Roles->all();
