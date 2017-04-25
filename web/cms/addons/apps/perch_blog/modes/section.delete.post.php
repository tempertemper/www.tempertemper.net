<?php

    echo $Form->form_start();
    
    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the section %s?', $details['sectionTitle']);
        echo $Form->form_start();
        echo $Form->hidden('sectionID', $details['sectionID']);
		echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path());


        echo $Form->form_end();
    }
