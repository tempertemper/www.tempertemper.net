<?php
    $heading = $Lang->get('Search results');

    if (PerchUtil::get('q')) {
        $heading = $Lang->get('Search results for ‘%s’', PerchUtil::html(PerchUtil::get('q')));
    }

    echo $HTML->title_panel([
        'heading' => $heading,
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Results',
        'link'     => '/core/runway/search/',
        'icon'     => 'core/search',
    ]);
    
    echo $Smartbar->render();

            
    echo $results;