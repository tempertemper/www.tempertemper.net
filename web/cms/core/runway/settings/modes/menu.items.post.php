<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Configuring sidebar menus'),
            'button'  => [
                        'text' => $Lang->get('Add item'),
                        'link' => '/core/settings/menu/edit/?pid='.$Section->id(),
                        'icon' => 'core/plus',
                        'priv' => 'perch.menus.manage',
                    ]
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
        ]
        
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Menu options',
        'link'     => '/core/settings/menu/section/edit/?id='.PerchUtil::get('id'),
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

    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Title',
            'value'     => 'itemTitle',
            'sort'      => 'itemTitle',
            'edit_link' => '../edit',
        ]);
    $Listing->add_col([
            'title'     => 'Type',
            'value'     => 'itemType',
            'sort'      => 'itemType',
        ]);
    $Listing->add_col([
            'title'     => 'Detail',
            'value'     => 'itemValue',
            'sort'      => 'itemValue',
        ]);
    $Listing->add_col([
            'type'      => 'status',
            'title'     => 'Active',
            'value'     => 'itemActive',
            'sort'      => 'itemActive'
        ]);
    $Listing->add_delete_action([
            'priv'   => 'perch.menus.manage',
            'inline' => true,
            'path'   => '../delete',
            'display' => function($item) {
                return !$item->itemPersists();
            }
        ]);

    echo $Listing->render($items);
