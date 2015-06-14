<?php 
    # Side panel
    echo $HTML->side_panel_start();
    echo $HTML->para('Delete a form and all of its responses.');
    echo $HTML->side_panel_end();
    
    
    # Main panel
    echo $HTML->main_panel_start(); 
    include('_subnav.php');

    echo $HTML->heading1('Deleting a Form');




    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the form %s and all if its responses?', $details['formTitle']);
        echo $Form->form_start();
        echo $Form->hidden('formID', $details['formID']);
		echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path());


        echo $Form->form_end();
    }
    
    echo $HTML->main_panel_end();

?>