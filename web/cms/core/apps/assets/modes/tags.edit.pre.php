<?php
    
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $Tags = new PerchAssets_Tags();

    $Form = $API->get('Form');
	
    $message = false;
        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $tagID = (int) $_GET['id'];    
        $Tag = $Tags->find($tagID);
    }else{
        PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/assets/tags/');
    }
    


    $req = array();
    $req['tagTitle']   = "Required";
    $req['tagSlug']   = "Required";

    $Form->set_required($req);
    
    if ($Form->posted() && $Form->validate()) {
    
		$postvars = array('tagTitle', 'tagSlug');
		
    	$data = $Form->receive($postvars);
          
    	if (is_object($Tag)) {
    	    $Tag->update($data);
    	    $Alert->set('success', PerchLang::get('The tag has been successfully updated.'));
    	}	    
    }
    
    
    
    $details    = $Tag->to_array();