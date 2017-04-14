<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Delete backup plan'),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete this backup plan?'));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/settings/backup/'
            ]);
    echo $Form->form_end();
