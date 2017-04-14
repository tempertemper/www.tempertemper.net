<?php
    
    echo $HTML->title_panel([
    'heading' => $Lang->get('Listing categories in ‘%s’ set', $Set->setTitle()),
    'button'  => [
                    'text' => $Lang->get('Add category'),
                    'link' => '/core/apps/categories/edit/?sid='.$Set->id(),
                    'icon' => 'core/plus',
                    'priv' => 'categories.create',
                ]
    ], $CurrentUser);


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


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Category',
            'value'     => 'catTitle',
            'edit_link' => '../edit',
            'depth'     => function($item){
                return (int)$item->catDepth()-1;
            },
            'icon'      => 'core/chart-pie',
            'create_link' => [
                                'title' => 'New sub-category',
                                'link'  => '../edit',
                                'priv'  => 'categories.create',
                             ]
        ]);

    $Listing->add_col([
            'title' => 'Path',
            'value' => 'catPath',
        ]);

    $Listing->add_delete_action([
            'priv'   => 'categories.delete',
            'inline' => true,
            'path'   => PERCH_LOGINPATH.'/core/apps/categories/delete',
        ]);

    echo $Listing->render($cats);