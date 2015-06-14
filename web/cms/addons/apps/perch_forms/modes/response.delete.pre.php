<?php
    
    $Responses = new PerchForms_Responses($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    	
	$message = false;
	
	if (isset($_GET['id']) && $_GET['id']!='') {
	    $Response = $Responses->find($_GET['id']);
	}else{
	    PerchUtil::redirect($API->app_path());
	}
	

    if ($Form->submitted()) {
    	if (is_object($Response)) {
    	    $formID = $Response->formID();
    	    $Response->delete();

            if ($Form->submitted_via_ajax) {
                echo $API->app_path().'/responses/?id='.$formID;
                exit;
            }else{
                PerchUtil::redirect($API->app_path().'/responses/?id='.$formID);
            }


            
        }else{
            $message = $HTML->failure_message('Sorry, that response could not be deleted.');
        }
    }

    
    
    $details = $Response->to_array();



?>