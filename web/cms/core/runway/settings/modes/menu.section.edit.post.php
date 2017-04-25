<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Configuring sidebar menus'),
        ], $CurrentUser);

	   

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => false,
        'type'     => 'breadcrumb',
        'links'    => [
            [
                'title'    => 'Menus',
                'link'     => '/core/settings/menu/',
            ],
            [
                'title'    => (is_object($MenuItem) ? $MenuItem->itemTitle() : 'New menu'),
                'link'     => '/core/settings/menu/'.(is_object($MenuItem) ? '/items/?id='.$MenuItem->id() : ''),
            ],
        ]
        
    ]);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Menu options',
        'link'     => '/core/settings/menu/section/edit/?id='.(is_object($MenuItem) ? $MenuItem->id() : $parentID),
        'icon'     => 'core/o-toggles',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Reorder',
        'link'     => '/core/settings/menu/reorder/',
        'icon'     => 'core/menu',
        'position' => 'end',
    ]);
    echo $Smartbar->render();



    // Sub head
    echo $HTML->heading2('Details');

    // Output the edit form
    echo $Form->form_start();
    
    echo $Form->fields_from_template($Template, $details, [], false);

    echo $Form->submit_field();
    echo $Form->form_end();
    