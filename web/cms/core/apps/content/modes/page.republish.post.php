<?php  
    echo $HTML->title_panel([
        'heading' => $Lang->get('Republishing pages'),
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

        $Smartbar->add_item([
            'active' => false,
            'title'  => 'All',
            'link'   => '/core/apps/content/'
        ]); 

        $Smartbar->add_item([
            'active' => false,
            'title'  => 'New',
            'link'   => '/core/apps/content/?filter=new'
        ]); 


        $Smartbar->add_item([
            'title'    => 'Reorder Pages',
            'link'     => '/core/apps/content/reorder/',
            'priv'     => 'content.pages.reorder',
            'icon'     => 'core/menu',
            'position' => 'end',
        ]);  

        $Smartbar->add_item([
            'active'   => true,
            'title'    => 'Republish',
            'link'     => '/core/apps/content/republish/',
            'priv'     => 'content.pages.republish',
            'icon'     => 'core/documents',
            'position' => 'end',
        ]);  

    echo $Smartbar->render();


    if ($republish) {
        echo '<div class="inner">
                <ul class="progress-list">';
        $Regions->republish_all(true);
        echo '</ul>
            </div>';
    }else{

        echo $Form->form_start();
        echo $HTML->wrap('div.instructions p', $Lang->get('Are you sure you wish to republish all pages?'));
        echo $HTML->submit_bar([
            'button' => $Form->submit('btnsubmit', 'Republish', 'button'),
            'cancel_link' => '/core/apps/content/'
            ]);
        echo $Form->form_end();
    
    } // republish 