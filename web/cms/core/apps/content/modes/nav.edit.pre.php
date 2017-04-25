<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    
    $NavGroups  = new PerchContent_NavGroups;
    $Pages      = new PerchContent_Pages;

    $Form = $API->get('Form');
	
    $message = false;
        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $groupID = (int) $_GET['id'];    
        $NavGroup = $NavGroups->find($groupID);
    }else{
        $groupID = false;
        $NavGroup = false;
    }

    $req = array();
    $req['groupTitle']   = "Required";

    $Form->set_required($req);
    
    if ($Form->posted() && $Form->validate()) {
    
		$postvars = array('groupTitle');
		
    	$data = $Form->receive($postvars);
    	
    	if (is_object($NavGroup)) {
    	    $NavGroup->update($data);
    	    $Alert->set('success', PerchLang::get('Your navigation group has been successfully updated.'));
        }else{
            $data['groupSlug'] = PerchUtil::urlify($data['groupTitle']);
            $NavGroup = $NavGroups->create($data);
            if (is_object($NavGroup)) {
                PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/navigation/edit/?id='.$NavGroup->id().'&created=1');
            }else{
                $Alert->set('failure', PerchLang::get('There was a problem creating the navigation group.'));
            }
        }

    }

    if (isset($_GET['created'])) {
        $Alert->set('success', PerchLang::get('Your navigation group has been successfully created.'));
    }    
    
    
    if (is_object($NavGroup)) {
        $details = $NavGroup->to_array();    
    }else{
        $details = array();
    }
    

