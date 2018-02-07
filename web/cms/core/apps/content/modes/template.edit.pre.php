<?php

    $API    = new PerchAPI(1.0, 'content');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    $Paging = $API->get('Paging');
    
    $Templates  = new PerchContent_PageTemplates;
    $Pages      = new PerchContent_Pages;
    $NavGroups  = new PerchContent_NavGroups;

    if (PERCH_RUNWAY) {
        $PageRoutes  = new PerchPageRoutes;
    }

    $Form = new PerchForm('edit');
	
    $message = false;
        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $templateID = (int) $_GET['id'];    
        $Template = $Templates->find($templateID);
    }else{
        PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/page/templates/');
    }
    
    
    $Form = new PerchForm('editpage');

    $req = array();
    $req['templateTitle']   = "Required";

    $Form->set_required($req);
    
    if ($Form->posted() && $Form->validate()) {
    
		$postvars = array('templateTitle', 'optionsPageID', 'templateReference');
		
    	$data = $Form->receive($postvars);
    	
        // navgroups
        if (isset($_POST['navgroups']) && PerchUtil::count($_POST['navgroups'])) {
            $data['templateNavGroups'] = implode(',', $_POST['navgroups']);
        }else{
            $data['templateNavGroups'] = '';
        }



    	if (is_object($Template)) {
    	    $Template->update($data);
    	    $Alert->set('success', PerchLang::get('Your master page has been successfully edited.'));
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
                            'templateID'=>$Template->id(),
                            'templatePath' => $Template->templatePath(),
                            'routePattern' => $pattern,
                            'routeOrder'   => 99,
                            ));
                    }
                    
                }
            }
            $Form->reset_field('new_pattern');

        }




    }
    

    if (PERCH_RUNWAY) {
        $routes      = $PageRoutes->get_routes_for_template($Template->id()); 
    }
    
    
    $details    = $Template->to_array();
    $navgroups  = $NavGroups->all();

