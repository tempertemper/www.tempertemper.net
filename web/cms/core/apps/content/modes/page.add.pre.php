<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $Pages = new PerchContent_Pages;
    $PageTemplates  = new PerchContent_PageTemplates;

    $PageTemplates->find_and_add_new_templates();
    
    $Page  = false;
    
    $Templates = new PerchContent_PageTemplates;
    
    // Find the page
    if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
        $parentID = (int) $_GET['pid'];
        $ParentPage = $Pages->find($parentID);
    }else{
        $parentID = false;
        $ParentPage = false;
    }
    
    // Check permissions
    if (!$CurrentUser->has_priv('content.pages.create')) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

    
    $Form = new PerchForm('addpage');

    $req = array();
    $req['pageTitle']    = "Required";
    $req['file_name']    = "Required";
    $req['pageParentID'] = "Required";

    $Form->set_required($req);
    
    if ($Form->posted()) {
        if ($Form->validate()) {
        	$postvars = array('pageTitle', 'pageNavText', 'file_name', 'pageParentID', 'templateID', 'create_folder');
        	$data = $Form->receive($postvars);
        	    	
            $data['pageNew']        = 1;
            $data['pageCreatorID']  = $CurrentUser->id();
            $data['pageModified']   = date('Y-m-d H:i:s');
            $data['pageAttributes'] = '';
        	

            if (PERCH_RUNWAY) {
                $PageTemplate = $PageTemplates->find($data['templateID']);
                
                if ($PageTemplate) {
                    $data['pageTemplate'] = $PageTemplate->templatePath();    
                }else{
                    $data['pageTemplate'] = '';
                }

                $Page = $Pages->create_without_file($data);
            }else{
                if (!isset($data['templateID']) || $data['templateID'] == '') {
                    $Page = $Pages->create_without_file($data);
                }else{
                    $Page = $Pages->create_with_file($data);              
                }    
            }

        	        	    
    	    
    	    if (is_object($Page)) {

    			$Pages->order_new_pages();
    		
    	        PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/page/details/?id='.$Page->id().'&created=true');
    	    }else{
    	        $message = '';
    	        
    	        $errors = $Pages->get_errors();
    	        if (PerchUtil::count($errors)) {
    	            foreach($errors as $error) {
    	                $Alert->set('error', PerchLang::get($error));
    	            }
    	        }
    	        
    	        $Alert->set('error', PerchLang::get('Sorry, that page could not be created.'));
    	        
    	    }
        	  	
        }else{
            PerchUtil::debug('No validate');
        }
    	
    	
    }else{
        PerchUtil::debug('Not posted');
    }

    
    $details = array();
