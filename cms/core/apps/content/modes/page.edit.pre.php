<?php

    $Pages = new PerchContent_Pages;
    $Regions = new PerchContent_Regions;
    $Page  = false;

    $NavGroups  = new PerchContent_NavGroups;

    // Find the page
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Page = $Pages->find($id);
    }
    
    // Check we have a page
    if (!$Page || !is_object($Page)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }
    
    // Check permissions
    if (!$CurrentUser->has_priv('content.pages.edit')) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

   
    $Form = new PerchForm('editpage');

    $req = array();
    $req['pagePath']    = "Required";

    $Form->set_required($req);
    
    if ($Form->posted() && $Form->validate()) {
    	$postvars = array('pagePath', 'pageSubpagePath', 'pageHidden', 'pageAccessTags', 'pageAttributeTemplate');
    	$data = $Form->receive($postvars);
    	
    	if (!isset($data['pageHidden'])) $data['pageHidden'] = '0';
    	
    	$data['pageSubpagePath'] = '/'.ltrim($data['pageSubpagePath'], '/');
    	$_POST['pageSubpagePath'] = $data['pageSubpagePath'];

        $data['pageModified'] = date('Y-m-d H:i:s');

    	
    	if (isset($_POST['subpage_roles']) && PerchUtil::count($_POST['subpage_roles'])) {
    	    $roles = $_POST['subpage_roles'];
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
    	        $data['pageSubpageRoles'] = implode(',', $new_roles);
    	    }
    	}else{
    	    $data['pageSubpageRoles'] = '';
    	}

        $error = false;

        // Move page?
        if (isset($_POST['move']) && $_POST['move']=='1') {

            $Pages->find_site_path();
            $new_path = $data['pagePath'];
            list($move_result, $move_message) = $Page->move_file($new_path);

            if (!$move_result) {
                $Alert->set('error', PerchLang::get($move_message));
                $error = true;
            }

        }
    
    	if (!$error) {

        	$Page->update($data);
        	
        	if (isset($_POST['pageParentID'])) {
        	    $parentID = (int) $_POST['pageParentID'];
        	    
        	    if ($parentID != $Page->pageParentID()) {
        	        $Page->update_tree_position($parentID, false, $cascade=true);
        	    }
        	    
        	}
        	
        	
        	// update regions on this page
        	$Regions = new PerchContent_Regions;
        	$regions = $Regions->get_for_page($Page->id());
        	
        	if (PerchUtil::count($regions)) {
        	    foreach($regions as $Region) {
                    if ($Region->regionPage()!='*') {
                        $region_data = array();
                        $region_data['regionPage'] = $data['pagePath'];
                        $Region->update($region_data);
                    }
        	    }
        	}

            // navgroups
            
            if (isset($_POST['navgroups']) && PerchUtil::count($_POST['navgroups'])) {
                $Page->update_navgroups($_POST['navgroups']);
            }else{
                $Page->remove_from_navgroups();
            }
       	
        	
        	$Alert->set('success', PerchLang::get('Successfully updated'));
        }
    }

    $created = false;
    
    if (isset($_GET['created'])) {
        $Alert->set('success', PerchLang::get('Your page has been successfully created. Return to %spage listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/content/">', '</a>'));
        $created = true;
    }


    $Roles = new PerchUserRoles();
    $roles = $Roles->all();
    
    $details = $Page->to_array();

    $navgroups = $NavGroups->all();
?>