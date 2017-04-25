<?php 
    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the author %s?', '<strong>'.trim($details['authorGivenName'].' '.$details['authorFamilyName']).'</strong>');
        echo $Form->form_start();
		echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/authors/');
        echo $Form->form_end();
    }
    