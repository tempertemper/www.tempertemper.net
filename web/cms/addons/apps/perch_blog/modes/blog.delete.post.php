<?php

    # Side panel
    echo $HTML->side_panel_start();
    echo $HTML->heading3('Delete Blog');
    echo $HTML->para('Delete a blog here.');
    echo $HTML->side_panel_end();


    # Main panel
    echo $HTML->main_panel_start();

    include('_subnav.php');

    echo $Form->form_start();

    if ($message) {
        echo $message;
    }else{
        echo $HTML->warning_message('Are you sure you wish to delete the blog %s?', $details['blogTitle']);
        echo $Form->form_start();
        echo $Form->hidden('blogID', $details['blogID']);
		echo $Form->submit_field('btnSubmit', 'Delete', $API->app_path());


        echo $Form->form_end();
    }

    echo $HTML->main_panel_end();
