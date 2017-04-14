<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $User = $Users->find($id);
    }


    if (!$User || !is_object($User)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/users');
    }
    
    
    $Roles = new PerchUserRoles();
    $roles = $Roles->all();



    /* --------- Edit User Form ----------- */

    $Form 	= new PerchForm('user', false);

    $req = array();
    $req['userUsername']   = "Required";
    $req['userGivenName']  = "Required";
    $req['userFamilyName'] = "Required";
    $req['userEmail']      = "Required";

    if (PERCH_PARANOID) {
        $req['currentPassword'] = "Required";    
    }
    
    
    if ($User->id() != $CurrentUser->id()){
        $req['roleID']       = "Required";
    }


    $Form->set_required($req);

    $validation = array();
    $validation['userUsername']	= array("username", PerchLang::get("Username not available, try another."), array('userID'=>$User->id()));
    $validation['userEmail']	= array("email", PerchLang::get("Email incomplete or already in use."), array('userID'=>$User->id()));

    if (PERCH_PARANOID) {
        $CUser = $Users->find($CurrentUser->id());
        $validation['currentPassword'] = array("admin_auth", PerchLang::get("Please provide your password to authenticate this change."), array('user'=>&$CUser));
    }

    $Form->set_validation($validation);

    if ($Form->posted() && $Form->validate()) {

		$data		= array();
		$postvars 	= array('userUsername', 'userGivenName', 'userFamilyName','userEmail','roleID');
		$data = $Form->receive($postvars);

		$User->update($data);
		$Alert->set('success', PerchLang::get('User successfully updated.'));

    }
    
	
	if (isset($_POST['resetPwd']) && $_POST['resetPwd']=='1') {
		$User->send_password_recovery_link();
		$Alert->set('success', PerchLang::get('Password recovery instructions have been sent by email.'));
	}

    $details = $User->to_array();

