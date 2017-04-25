<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    $User = $Users->find($CurrentUser->id());

    if (!is_object($User)) {
        PerchUtil::redirect(PERCH_LOGINPATH);
    }

    /* --------- Edit User Form ----------- */

    $Form 	= new PerchForm('user', false);

    $req = array();
    $req['userGivenName']  = "Required";
    $req['userFamilyName'] = "Required";
    $req['userEmail']      = "Required";
    

    $Form->set_required($req);

    $validation = array();
    $validation['userEmail']	= array("email", PerchLang::get("Email incomplete or already in use."), array('userID'=>$User->id()));

    $validation['userPassword']	= array("password", PerchLang::get("Your passwords must match and meet complexity requirements."), array('user'=>&$User));
    
    if (PERCH_PARANOID) {
        $validation['userPassword'] = array("change_password", PerchLang::get("Your existing password must be correct, and the new passwords must match and meet complexity requirements."), array('user'=>&$User, 'current_password'=>'currentPassword'));
    }

    $Form->set_validation($validation);

    if ($Form->posted() && $Form->validate()) {

		$data		= array();
		$postvars 	= array('userGivenName', 'userFamilyName','userEmail','userPassword');
		$data = $Form->receive($postvars);

		if (isset($data['userPassword'])) {
		    if ($data['userPassword'] != '') {
                $User->set_new_password($data['userPassword']);
		    }
            unset($data['userPassword']);
		}

		$User->update($data);
		$Alert->set('success', PerchLang::get('Your details have been successfully updated.'));

		// Language setting
        $postvars = array( 'lang');
        $data     = $Form->receive($postvars);

    	foreach($data as $key=>$value) {
    	    $Settings->set($key, $value, $User->id());
    	}

    	$Settings->reload();

        $Lang = PerchLang::fetch();
        $Lang->reload();
    }else {
        if ($User->msg) {
            $Alert->set('error', PerchLang::get('Password rules: '.$User->msg));
        }
    }

    $details = $User->to_array();
    $settings = $Settings->get_as_array(true);