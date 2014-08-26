<?php

    $Roles = new PerchUserRoles();
    $roles = $Roles->all();

    
    /* --------- New User Form ----------- */

    $fCreateUser 	= new PerchForm('createuser', false);

    $req = array();
    $req['userUsername']   = "Required";
    $req['userGivenName']  = "Required";
    $req['userFamilyName'] = "Required";
    $req['userEmail']      = "Required";
    $req['userPassword']   = "Required";
    $req['roleID']         = "Required";


    $fCreateUser->set_required($req);

    $validation = array();
    $validation['userUsername']	= array("username", PerchLang::get("Username not available, try another."));
    $validation['userEmail']	= array("email", PerchLang::get("Email incomplete or already in use."));

    $fCreateUser->set_validation($validation);

    if ($fCreateUser->posted() && $fCreateUser->validate()) {

		$data		= array();
		$postvars 	= array('userUsername', 'userGivenName', 'userFamilyName','userEmail','userPassword','roleID');
		$data = $fCreateUser->receive($postvars);

        $sendEmail  = false;        
        if (isset($_POST['sendEmail']) && $_POST['sendEmail']=='1') $sendEmail = true;
        
		$Users->create($data, $sendEmail);

		$Alert->set('success', PerchLang::get('User successfully created.'));

		$fCreateUser->clear();
    }


?>