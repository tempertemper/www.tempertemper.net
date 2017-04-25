<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Deleting the ‘%s’ Navigation Group', PerchUtil::html($NavGroup->groupTitle())),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete the group ‘%s’?', PerchUtil::html($NavGroup->groupTitle())));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/apps/content/navigation/',
            ]);
    echo $Form->form_end();
