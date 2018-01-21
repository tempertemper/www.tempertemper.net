<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    
    $Regions = new PerchContent_Regions;
    $Region  = false;

    // Find the region
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Region = $Regions->find($id);
        
    }

    if (!$Region || !is_object($Region)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }
    
    $options     = $Region->get_options();
    
    
        
    // set the current user
    $Region->set_current_user($CurrentUser->id());
    
    /* --------- Options Form ----------- */
    

    $Form = new PerchForm('options');
    
    if ($Form->posted() && $Form->validate()) {
        $postvars = array('regionMultiple', 'regionSearchable');
    	$data = $Form->receive($postvars);

        if ($CurrentUser->has_priv('content.regions.templates') && isset($_POST['regionTemplate'])) {
            $data['regionTemplate'] = $_POST['regionTemplate'];
            $data['regionKey'] = $_POST['regionKey'];
        }
        
        if (!isset($data['regionMultiple'])) {
            $data['regionMultiple'] = 0;
            $Region->truncate(1);
        }
        if (!isset($data['regionSearchable'])) {
            $data['regionSearchable'] = 0;
        }
    	
    	if (isset($_POST['edit_roles']) && PerchUtil::count($_POST['edit_roles'])) {
    	    $roles = $_POST['edit_roles'];
    	    $new_roles = array();
    	    foreach($roles as $role) {
    	        $role = trim($role);
    	        if ($role=='*') {
    	            $new_roles = array('*');
    	            break;
    	        }
    	        
    	        $new_roles[] = (int) $role;
    	    }
    	    
    	    if (PerchUtil::count($new_roles)) {
    	        $data['regionEditRoles'] = implode(',', $new_roles);
    	    }
    	}else{
    	    $data['regionEditRoles'] = '';
    	}
    	
        $Region->update($data);
        
        // sharing
        $postvars = array('contentShared');
        $data = $Form->receive($postvars);
        if (isset($data['contentShared'])) {
            if ($Region->regionPage()!='*'){
                $Region->make_shared();
            }
        }else{
            if ($Region->regionPage()=='*') {
                $unshare = $Region->make_not_shared();
                if ($unshare==false) {
                    $Alert->set('error', PerchLang::get('Sorry, the region cannot be unshared as the page it originally belonged to has gone.'));
                }
            }
        }
        
        
        
        // opts
        
        $postvars = array('sortOrder', 'sortField', 'adminOnly', 'limit', 'searchURL', 'addToTop', 'edit_mode', 'column_ids');
    	$data = $Form->receive($postvars);

        if (isset($_POST['title_delimit'])) {
            $data['title_delimit'] = $_POST['title_delimit']; // so whitespace doesn't get trimmed. *facepalm*
        }
    	
    	if (!isset($data['adminOnly'])) {
    	    $data['adminOnly'] = 0;
    	}
    	
    	if (!isset($data['addToTop'])) {
            $data['addToTop'] = 0;
        }
    	
    	if (!isset($data['limit'])) {
    	    $data['limit'] = false;
    	}
    	
    	if (!isset($data['edit_mode'])) {
    	    $data['edit_mode'] = 'listdetail';
    	}

        if (!isset($data['column_ids'])) {
            $data['column_ids'] = [];
        }
    	
    	$Region->set_options($data);

        // Reset
        if (isset($_POST['regionReset']) && $_POST['regionReset']=='1') {
            $Region->truncate(0);
        }
    	
        $Region->sort_items();
        $Region->publish();
        $Region->index();
        
        $Alert->set('success', PerchLang::get('Successfully updated'));
        
        
        $options     = $Region->get_options();
        
    }



    $Roles = new PerchUserRoles();
    $roles = $Roles->all();
    
