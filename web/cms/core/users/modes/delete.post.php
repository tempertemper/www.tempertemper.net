<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Deleting User %s', PerchUtil::html($User->userGivenName() . ' ' . $User->userFamilyName())),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete %s?', PerchUtil::html($User->userGivenName() . ' ' . $User->userFamilyName())));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/users/',
            ]);
    echo $Form->form_end();
