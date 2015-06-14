<?php
   
    # Side panel
    echo $HTML->side_panel_start();
    echo $HTML->para('Delete a response to a form.');
    echo $HTML->side_panel_end();
    
    
    # Main panel
    echo $HTML->main_panel_start(); 
    include('_subnav.php');

    echo $HTML->heading1('Deleting a Response');


    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete this response?');
        echo $Form->form_start();
        echo $Form->hidden('responseID', $details['responseID']);
		echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path().'/responses/?id='.$details['formID']);


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();

?>