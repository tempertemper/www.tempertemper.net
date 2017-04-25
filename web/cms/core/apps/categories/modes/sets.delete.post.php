<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Delete Category Set ‘%s’', $Set->setTitle()),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete this category set and its categories?'));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/apps/categories/sets/?id='.$Set->id()
            ]);
    echo $Form->form_end();
