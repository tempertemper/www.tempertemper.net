<?php

    $Pages = new PerchContent_Pages;
    $Regions = new PerchContent_Regions;
    $Page  = false;

    $NavGroups  = new PerchContent_NavGroups;
    $PageTemplates  = new PerchContent_PageTemplates;

    if (PERCH_RUNWAY) {
        $PageRoutes  = new PerchPageRoutes();
        $Collections = new PerchContent_Collections();
    }

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

    $ParentPage = $Pages->find($Page->pageParentID());
   
    $Form = new PerchForm('editpage');

    $req = array();
    $req['pagePath']    = "Required";

    $Form->set_required($req);
    
    if ($Form->posted() && $Form->validate()) {
    	$postvars = array('pagePath', 'pageSubpagePath', 'pageHidden', 'pageAccessTags', 'pageAttributeTemplate');

        if (PERCH_RUNWAY) {
            $postvars[] = 'templateID';
        }


    	$data = $Form->receive($postvars);
    	
    	if (!isset($data['pageHidden'])) $data['pageHidden'] = '0';
    	
        if (!PERCH_RUNWAY) {
            $data['pageSubpagePath'] = '/'.ltrim($data['pageSubpagePath'], '/');
            $_POST['pageSubpagePath'] = $data['pageSubpagePath'];
        }

        $data['pageModified'] = date('Y-m-d H:i:s');

        if (isset($_POST['collections']) && PerchUtil::count($_POST['collections'])) {
            $collections = $_POST['collections'];
            $new_collections = array();
            foreach($collections as $collection) {
                $collection = trim($collection);               
                $new_collections[] = (int) $collection;
            }
            
            if (PerchUtil::count($new_collections)) {
                $data['pageCollections'] = implode(',', $new_collections);
            }
        }else{
            $data['pageCollections'] = '';
        }

    	
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

        // Subpage templates
        if (PERCH_RUNWAY && isset($_POST['subpage_templates']) && PerchUtil::count($_POST['subpage_templates'])) {
            $templates = $_POST['subpage_templates'];
            $new_templates = array();
            foreach($templates as $tpl) {
                $tpl = trim($tpl);
                if ($tpl=='*') {
                    $new_templates = array('*');
                    break;
                }
                
                $new_templates[] = (int) $tpl;
            }
            
            if (PerchUtil::count($new_templates)) {
                $data['pageSubpageTemplates'] = implode(',', $new_templates);
            }
        }else{
            $data['pageSubpageTemplates'] = '';
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

            if (PERCH_RUNWAY) {
                $PageTemplate = $PageTemplates->find($data['templateID']);
                if ($PageTemplate) {
                    $data['pageTemplate'] = $PageTemplate->templatePath();    
                }else{
                    $data['pageTemplate'] = '';
                }
            }
            

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
       	

            if (PERCH_RUNWAY) {

                // routes
                $routes = $Form->find_items('routePattern_');
                if (count($routes)) {
                    foreach($routes as $routeID=>$pattern) {
                        $PageRoute = $PageRoutes->find($routeID);

                        if (!is_object($PageRoute)) continue;

                        if (trim($pattern)!='') {
                            $pattern = trim($pattern, '/');
                            $PageRoute->update(array('routePattern'=>$pattern));
                        }else{
                            $PageRoute->delete();
                        }
                    }
                } 

                $new_routes = $Form->receive(array('new_pattern'));
                if (count($new_routes)) {
                    foreach($new_routes as $pattern) {
                        if (trim($pattern)!='') {
                            $PageRoute = $PageRoutes->create(array(
                                'pageID'=>$Page->id(),
                                'routePattern' => $pattern
                                ));
                        }
                        
                    }
                }
                $Form->reset_field('new_pattern');

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

    if (PERCH_RUNWAY) {
        $routes      = $PageRoutes->get_routes_for_page($Page->id()); 
        $collections = $Collections->all();   
    }
