<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    $Pages = new PerchContent_Pages;
    $Regions = new PerchContent_Regions;
    $Page  = false;

    $NavGroups  = new PerchContent_NavGroups;
    $PageTemplates  = new PerchContent_PageTemplates;

    $PageTemplates->find_and_add_new_templates();

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
    //$req['pagePath']    = "Required";

    $Form->set_required($req);
    
    if ($Form->posted() && $Form->validate()) {
        //$postvars = array('pageSubpagePath', 'pageHidden', 'pageAccessTags', 'pageAttributeTemplate');
    	$postvars = array('pageSubpagePath', 'pageHidden', 'pageAccessTags', 'pageAttributeTemplate');

        if (PERCH_RUNWAY) {
            $postvars[] = 'templateID';
        }


    	$data = $Form->receive($postvars);
    	
    	if (!isset($data['pageHidden'])) $data['pageHidden'] = '0';
    	
        if (!PERCH_RUNWAY) {
            if (!isset($data['pageSubpagePath'])) $data['pageSubpagePath'] = false;
            $data['pageSubpagePath'] = '/'.ltrim($data['pageSubpagePath'], '/');
            $_POST['pageSubpagePath'] = $data['pageSubpagePath'];
        }else{
            $data['pageSortPath'] = PerchUtil::strip_file_extension(str_replace(array('/'.PERCH_DEFAULT_DOC), '', $Page->pagePath()));
        }

        $data['pageModified'] = date('Y-m-d H:i:s');

        if (isset($_POST['collections']) && PerchUtil::count($_POST['collections'])) {
            PerchUtil::mark('Y');
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
            PerchUtil::mark('N');
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
                        $region_data['regionPage'] = $Page->pagePath();
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
    } else {
        PerchUtil::debug($Form);
    }

    $created = false;
    
    if (isset($_GET['created'])) {
        $Alert->set('success', PerchLang::get('Your page has been successfully created. Return to %spage listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/content/" class="notification-link">', '</a>'));
        $created = true;
    }


    $Roles = new PerchUserRoles();
    $roles = $Roles->all();
    
    $details = $Page->to_array();

    $navgroups = $NavGroups->all();

    if (PERCH_RUNWAY) {
        $collections = $Collections->all();   
    }



    if ($details['pageSubpagePath']==''){
        $details['pageSubpagePath'] = PerchUtil::strip_file_name($Page->pagePath());
    }
    
    $Pages->find_site_path();
    if (!PERCH_RUNWAY && !file_exists(PerchUtil::file_path(PERCH_SITEPATH.$details['pageSubpagePath']))) {
        $Alert->set('error', PerchLang::get('Subpage folder does not exist'));
        PerchUtil::debug(PerchUtil::file_path(PERCH_SITEPATH.$details['pageSubpagePath']));
    }