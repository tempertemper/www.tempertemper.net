<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Configuring sidebar menus'),
            'button'  => [
                        'text' => $Lang->get('Add section'),
                        'link' => '/core/settings/menu/section/edit/',
                        'icon' => 'core/plus',
                        'priv' => 'perch.menus.manage',
                    ]
        ], $CurrentUser);

	   

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Menus',
        'link'     => '/core/settings/menu/',
        'icon'     => 'blocks/bookmark',
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
            'edit_link' => 'items',
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
            'path'   => 'delete',
            'display' => function($item) {
                return !$item->itemPersists();
            }
        ]);

    echo $Listing->render($top_level_menus);
