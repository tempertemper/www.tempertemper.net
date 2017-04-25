<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $Roles = new PerchUserRoles;
    $Privs = new PerchUserPrivileges;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Role = $Roles->find($id);
    }else{
        $id = false;
        $Role = false;
    }

    
    $Form = $API->get('Form');

    $req = array();
    $req['roleTitle']   = "Required";


    $Form->set_required($req);


    if ($Form->posted() && $Form->validate()) {
        
        PerchUtil::debug($_POST);

		$data		= array();
		$postvars 	= array('roleTitle');
		$data = $Form->receive($postvars);
		$data['roleSlug'] = PerchUtil::urlify($data['roleTitle']); 
		
		
		if (is_object($Role)) {
    		$Role->update($data);    
		}else{
		    $Role = $Roles->create($data);
		}
			
		
		
		$privs = $Form->find_items('privs-');
		$new_privs = array();
		
        if (PerchUtil::count($privs)) {
            foreach($privs as $category) {
                if (PerchUtil::count($category)) {
                    foreach($category as $item) {
                        $new_privs[] = $item;
                    }
                }
            }
        }
        
        $Role->set_privileges($new_privs);
		

	
		$Alert->set('success', PerchLang::get('Role successfully updated.'));

    }
    

    if ($Role) {
        $details = $Role->to_array();
        $existing_privs = $Privs->get_flat_for_role($Role);
    }else{
        $details = array();
        $existing_privs = array();
    }
    
    
    $privs = $Privs->get_for_edit();
    


?>