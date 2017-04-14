<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    
    $Roles = new PerchUserRoles();
    $roles = $Roles->all();

    
    /* --------- New User Form ----------- */

    $fCreateUser 	= new PerchForm('createuser', false);

    $req = array();
    $req['userUsername']   = "Required";
    $req['userGivenName']  = "Required";
    $req['userFamilyName'] = "Required";
    $req['userEmail']      = "Required";
    $req['roleID']         = "Required";

    if (!PERCH_PARANOID) {
        $req['userPassword']   = "Required";
        $req['userPassword2']   = "Required";
    }

    if (PERCH_PARANOID) {
        $req['currentPassword'] = "Required";
    }
    
    $fCreateUser->set_required($req);

    if ($fCreateUser->posted()) {

        $username = isset($_POST['userUsername']) ? $_POST['userUsername'] : '';
        $EmptyUser = new PerchUser(array('userUsername'=>$username, 'userID'=>null));

        $validation = array();
        $validation['userUsername'] = array("username", PerchLang::get("Username not available, try another."));
        $validation['userEmail']    = array("email", PerchLang::get("Email incomplete or already in use."));

        if (PERCH_PARANOID) {
            $CUser = $Users->find($CurrentUser->id());
            $validation['currentPassword'] = array("admin_auth", PerchLang::get("Please provide your password to authenticate this change."), array('user'=>&$CUser));
        }else{
            $validation['userPassword'] = array("password", PerchLang::get("Your passwords must match and meet complexity requirements."), array('user'=>&$EmptyUser));
        }
        

        $fCreateUser->set_validation($validation);


        if ($fCreateUser->validate()) {

            $data       = array();
            $postvars   = array('userUsername', 'userGivenName', 'userFamilyName', 'userEmail', 'roleID');
            
            if (!PERCH_PARANOID) {
                $postvars[] = 'userPassword';
            }

            $data = $fCreateUser->receive($postvars);

            if (PERCH_PARANOID) {
                $Users->create($data, true, true);
            }else{
                $sendEmail  = false;        
                if (isset($_POST['sendEmail']) && $_POST['sendEmail']=='1') $sendEmail = true;
                $Users->create($data, $sendEmail, false);
            }

#            PerchUtil::hold_redirects();
           

            $Alert->set('success', PerchLang::get('User successfully created.'));

            $fCreateUser->clear();
        }

    } 
