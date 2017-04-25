<?php 
    // Main heading - different if new vs edit mode
    if ($Category) {
        $heading = $Lang->get('Editing ‘%s’ Category', $HTML->encode($Category->catTitle()));
    }else{
        $heading = $Lang->get('Adding a New Category');         
    }

    echo $HTML->title_panel([
        'heading' => $heading,
        ]);

    echo $message;

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);


    $Smartbar->add_item([
            'active' => true,
            'type' => 'breadcrumb',
            'links' => [
                [
                    'title' => 'Sets',
                    'link'  => '/core/apps/categories/',
                ],
                [
                    'title' => $Set->setTitle(),
                    'link'  => '/core/apps/categories/sets/?id='.$Set->id(),
                    'translate' => false,
                ],
                [
                    'title' => ($Category ? $Category->catTitle() : $Lang->get('New category')),
                    'translate' => false,
                ]
            ],
        ]);

    $Smartbar->add_item([
            'active' => false,
            'title'  => 'Set Options',
            'link'   => '/core/apps/categories/sets/edit?id='.$Set->id(),
            'icon'   => 'core/o-toggles',
        ]);

    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Reorder',
            'link'     => '/core/apps/categories/reorder/?id='.$Set->id(),
            'position' => 'end',
            'icon'     => 'core/menu',
        ]);

    echo $Smartbar->render();



    // Sub head
    echo $HTML->heading2('Details');

    // Output the edit form
    $Form->add_another = true;
    echo $Form->form_complete($Template, $Category, 'catset:'.$Set->id());
    
