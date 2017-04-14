<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Deleting the ‘%s’ Region', PerchUtil::html($Region->regionKey())),
        ]); 

    echo $Form->form_start();

    $display_page = PerchUtil::html(($Region->regionPage() == '*' ? PerchLang::get('all pages.') : $Page->pageNavText()));

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete the region ‘%s’ from ‘%s’?', '<strong>'. PerchUtil::html($Region->regionKey()). '</strong>', $display_page));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' =>  '/core/apps/content/'
            ]);
    echo $Form->form_end();
