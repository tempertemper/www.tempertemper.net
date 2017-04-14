<?php

    echo $HTML->title_panel([
        'heading' => $Lang->get('Listing all category sets'),
        'button'  => [
                        'text' => $Lang->get('Add set'),
                        'link' => '/core/apps/categories/sets/edit/',
                        'icon' => 'core/plus',
                        'priv' => 'categories.sets.create',
                    ]
        ], $CurrentUser);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title' => 'Sets',
        'link'  => '/core/apps/categories/',
    ]);

    echo $Smartbar->render();


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Title',
            'value'     => 'setTitle',
            'sort'      => 'setTitle',
            'edit_link' => 'sets',
            'priv'      => 'categories.manage',
            'icon'      => 'core/o-connect',
        ]);
    $Listing->add_col([
            'title'     => 'Slug',
            'value'     => 'setSlug',
            'sort'      => 'setSlug',
        ]);
    $Listing->add_delete_action([
            'priv'   => 'categories.sets.delete',
            'inline' => true,
            'path'   => 'delete/set',
        ]);

    echo $Listing->render($sets);