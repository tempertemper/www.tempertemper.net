<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Deleting item %s', PerchUtil::html($MenuItem->itemTitle())),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete %s?', PerchUtil::html($MenuItem->itemTitle())));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/settings/menu/',
            ]);
    echo $Form->form_end();
