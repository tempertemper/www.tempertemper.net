<?php

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
