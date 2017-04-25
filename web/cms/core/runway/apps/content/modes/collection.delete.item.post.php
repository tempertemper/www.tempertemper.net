<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Delete item'),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete this item in the ‘%s’ collection?', PerchUtil::html($Collection->collectionKey())));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/apps/content/collections/?id='.$Collection->id()
            ]);
    echo $Form->form_end();
