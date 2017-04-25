<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Delete Collection ‘%s’', PerchUtil::html($Collection->collectionKey())),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete this collection and all its content?'));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/apps/content/manage/collections/'
            ]);
    echo $Form->form_end();
