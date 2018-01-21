<?php
	$Plans  = new PerchBackupPlans;
	$Runs   = new PerchBackupRuns;

	$runID    = (int) PerchUtil::get('id');
	$Run      = $Runs->find($runID);

	$Plan     = $Plans->find($Run->planID());

	$API  = new PerchAPI(1.0, 'core');
	$HTML = $API->get('HTML');


	$Form = $API->get('Form');
    
	$message = false;

    if ($Form->submitted()) {		
    	    	
        if ($Run->restore()) {
            $message = $HTML->success_message('Your backup has been successfully restored. You will now be asked to  %sreauthenticate%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/backup/" class="notification-link">', '</a>');
        }else{
            $message = $HTML->failure_message('Sorry, that backup could not be restored.');
            $message .= $HTML->failure_message($Run->message);
        }
        
    }