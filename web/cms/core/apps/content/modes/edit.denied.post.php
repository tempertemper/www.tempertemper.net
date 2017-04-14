<?php
	echo $HTML->title_panel([
            'heading' => $Lang->get('Content'),
        ]); 

	$Alert->set('alert', $Lang->get('Sorry, your account doesn\'t have access to edit this content.'));
    echo $Alert->output();
