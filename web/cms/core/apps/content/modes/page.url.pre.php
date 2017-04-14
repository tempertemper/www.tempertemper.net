<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    $Pages = new PerchContent_Pages;
    $Regions = new PerchContent_Regions;
    $Page  = false;

    if (PERCH_RUNWAY) {
        $PageRoutes  = new PerchPageRoutes();
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
    	$postvars = array('pagePath');

    	$data = $Form->receive($postvars);
    	
        if (PERCH_RUNWAY) {
            $data['pageSortPath'] = PerchUtil::strip_file_extension(str_replace(array('/'.PERCH_DEFAULT_DOC), '', $data['pagePath']));
        }

        $data['pageModified'] = date('Y-m-d H:i:s');    

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
        $Alert->set('success', PerchLang::get('Your page has been successfully created. Return to %spage listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/content/" class="notification-link">', '</a>'));
        $created = true;
    }

    if (PERCH_RUNWAY) {
        $routes      = $PageRoutes->get_routes_for_page($Page->id()); 
    }

    $details = $Page->to_array();