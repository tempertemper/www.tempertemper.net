<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Configuring sidebar menus'),
        ], $CurrentUser);

	   

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => true,
        'type'     => 'breadcrumb',
        'links'    => [
            [
                'title'    => 'Menus',
                'link'     => '/core/settings/menu/',
            ],
            [
                'title'    => $Section->itemTitle(),
                'link'     => '/core/settings/menu/items/?id='.$Section->id(),
            ],
            [
                'title'    => (is_object($MenuItem) ? $MenuItem->itemTitle() : 'New item'),
                'link'     => '/core/settings/menu/',
            ],
        ]
        
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Menu options',
        'link'     => '/core/settings/menu/section/edit/?id='.$parentID,
        'icon'     => 'core/o-toggles',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Reorder',
        'link'     => '/core/settings/menu/reorder/?id='.PerchUtil::get('id'),
        'icon'     => 'core/menu',
        'position' => 'end',
    ]);
    echo $Smartbar->render();



    // Sub head
    echo $HTML->heading2('Details');

    // Output the edit form
    echo $Form->form_start();
    
    // Fudge
    if (PerchUtil::count($details)) {
        if ($details['itemType'] == 'app') {

            if (PERCH_RUNWAY && is_numeric($details['itemValue'])) {
                $details['collection'] = $details['itemValue'];    
            } else {
                $details['app'] = $details['itemValue'];        
            }

            
        }

        if ($details['itemType'] == 'link') {
            $details['link'] = $details['itemValue'];    
        }
    }
    


    echo $Form->fields_from_template($Template, $details, [], false);

    echo $Form->submit_field();
    echo $Form->form_end();
    