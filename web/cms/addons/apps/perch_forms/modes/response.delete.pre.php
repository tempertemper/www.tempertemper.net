<?php
    
    $Responses = new PerchForms_Responses($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
    $Form->set_name('delete');
	
    $responses = [];
    	
	$message = false;
	
	if (isset($_GET['id']) && $_GET['id']!='') {
        if (is_array($_GET['id'])) {
            $ids = $_GET['id'];
            foreach($ids as $responseID) {
                $responses[] = $Responses->find($responseID);
            }
        } else {
            $responses[] = $Responses->find($_GET['id']);    
        }
	    
	}else{
	    PerchUtil::redirect($API->app_path());
	}
	

    if ($Form->submitted()) {
        if (PerchUtil::count($responses)) {
            foreach($responses as $Response) {
                if (is_object($Response)) {
                    $formID = $Response->formID();
                    $Response->delete();   
                }else{
                    $message = $HTML->failure_message('Sorry, that response could not be deleted.');
                }
            }

            if ($Form->submitted_via_ajax) {
                echo $API->app_path().'/responses/?id='.$formID;
                exit;
            }else{
                PerchUtil::redirect($API->app_path().'/responses/?id='.$formID);
            }
        }
    	
    }

    
    
    $details = $responses[0]->to_array();
