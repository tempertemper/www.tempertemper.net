<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Delete Content'),
        ]); 

    echo $Form->form_start();

    $display_page = PerchUtil::html(($Region->regionPage() == '*' ? PerchLang::get('all pages.') : $Page->pageNavText()));
    $Alert->set('warning', $Lang->get('Are you sure you wish to delete this item in the ‘%s’ region from ‘%s’?', PerchUtil::html($Region->regionKey()), $display_page));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' =>  '/core/apps/content/edit/?id='.$Region->id()
            ]);
    echo $Form->form_end();
