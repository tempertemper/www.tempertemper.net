<?php
    $API  = new PerchAPI(1.0, 'content');
    $HTML = $API->get('HTML');
    $Lang   = $API->get('Lang');
    
    $Collections = new PerchContent_Collections;
    $Regions = new PerchContent_Regions;
    $Collection  = false;

    // Find the region
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Collection = $Collections->find($id);
        
    }

    if (!$Collection || !is_object($Collection)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

    // App menu
    if ($Collection->collectionInAppMenu()) {
        $Perch = Perch::fetch();
        $Perch->set_section('collection:'.$Collection->collectionKey());    
    }
    
    $options     = $Collection->get_options();
    
    
        
    // set the current user
    $Collection->set_current_user($CurrentUser->id());
    
    /* --------- Options Form ----------- */
    

    $Form = new PerchForm('options');
    
    if ($Form->posted() && $Form->validate()) {
        $postvars = array('collectionSearchable', 'collectionInAppMenu');
    	$data = $Form->receive($postvars);

        if ($CurrentUser->has_priv('content.regions.templates') && isset($_POST['collectionTemplate'])) {
            $data['collectionTemplate'] = $_POST['collectionTemplate'];
        }
        
        if (!isset($data['collectionSearchable'])) {
            $data['collectionSearchable'] = 0;
        }

        if (!isset($data['collectionInAppMenu'])) {
            $data['collectionInAppMenu'] = 0;
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
    	        $data['collectionEditRoles'] = implode(',', $new_roles);
    	    }
    	}else{
    	    $data['collectionEditRoles'] = '';
    	}


        if (isset($_POST['publish_roles']) && PerchUtil::count($_POST['publish_roles'])) {
            $roles = $_POST['publish_roles'];
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
                $data['collectionPublishRoles'] = implode(',', $new_roles);
            }
        }else{
            $data['collectionPublishRoles'] = '';
        }
    	   	
    	
        $Collection->update($data);
        
        
        // opts
        
        $postvars = array('sortOrder', 'sortField', 'adminOnly', 'searchURL', 'addToTop', 'column_ids');
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

        if (!isset($data['column_ids'])) {
            $data['column_ids'] = [];
        }
    	
    	$Collection->set_options($data);

        // Reset
        if (isset($_POST['collectionReset']) && $_POST['collectionReset']=='1') {
            $Collection->delete_all_items();
        }
        
    	
        $Collection->sort_items();
        
        //$Collection->publish();
        //$Collection->index();
        
        $Alert->set('success', PerchLang::get('Successfully updated'));
        
        
        $options     = $Collection->get_options();
        
    }


    if (PerchUtil::get('created', false)) {
        $Alert->set('success', PerchLang::get('Collection successfully created'));
    }


    $Roles = new PerchUserRoles();
    $roles = $Roles->all();
    