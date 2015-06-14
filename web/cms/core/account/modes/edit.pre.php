<?php

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
    $validation['userPassword']	= array("password", PerchLang::get("Your passwords must match"));

    $Form->set_validation($validation);

    if ($Form->posted() && $Form->validate()) {

		$data		= array();
		$postvars 	= array('userGivenName', 'userFamilyName','userEmail','userPassword');
		$data = $Form->receive($postvars);
		
		if (isset($data['userPassword'])) {
		    if ($data['userPassword'] != '') {
		        
		        // check which type of password - default is portable
                if (defined('PERCH_NONPORTABLE_HASHES') && PERCH_NONPORTABLE_HASHES) {
                    $portable_hashes = false;
                }else{
                    $portable_hashes = true;
                }

                $Hasher = new PasswordHash(8, $portable_hashes);

                $clear_pwd  = $data['userPassword'];
                $data['userPassword'] = $Hasher->HashPassword($data['userPassword']);
		    }else{
		        unset($data['userPassword']);
		    }
		}

		$User->update($data);
		$Alert->set('success', PerchLang::get('Your details have been successfully updated.'));
		
		// Language setting
		$postvars = array( 'lang');
    	$data = $Form->receive($postvars);

    	foreach($data as $key=>$value) {
    	    $Settings->set($key, $value, $User->id());
    	}
    	
    	$Settings->reload();
        
        $Lang = PerchLang::fetch();
        $Lang->reload();

    }
    


    $details = $User->to_array();
    $settings = $Settings->get_as_array(true);
    PerchUtil::debug($settings);

?>