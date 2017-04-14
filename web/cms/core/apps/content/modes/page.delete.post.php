<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Deleting the %s Page', PerchUtil::html($Page->pageNavText())),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete the page ‘%s’?', PerchUtil::html($Page->pageNavText())));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/apps/content/',
            ]);
    echo $Form->form_end();
