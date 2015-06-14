<?php
    
    if (!$CurrentUser->has_priv('perch_forms.delete')) exit;
    
    $Forms = new PerchForms_Forms($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    
    $Form->set_name('delete');
	
	
	$message = false;
	
	if (isset($_GET['id']) && $_GET['id']!='') {
	    $ThisForm = $Forms->find($_GET['id'], true);
	}else{
	    PerchUtil::redirect($API->app_path());
	}
	

    if ($Form->submitted()) {
    	if (is_object($ThisForm)) {
    	    $ThisForm->delete();
            
            
            if ($Form->submitted_via_ajax) {
        	    echo $API->app_path();
        	    exit;
        	}else{
        	    PerchUtil::redirect($API->app_path().'/');
        	}
            
        }else{
            $message = $HTML->failure_message('Sorry, that form could not be deleted.');
        }
    }

    
    
    $details = $ThisForm->to_array();



?>