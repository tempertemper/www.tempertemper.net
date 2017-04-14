<?php

    echo $HTML->title_panel([
        'heading' => $Lang->get('Listing all asset tags')
        ]);    


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'type'  => 'breadcrumb',
        'links' => [
            [
                'title' => 'Assets',
                'link' => '/core/apps/assets/',
            ],
            [
                'title' => 'Tags',
                'link' => '/core/apps/assets/tags/',
            ],
        ],
    ]);

    echo $Smartbar->render();


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);

    $Listing->add_col([
            'title'     => 'Tag name',
            'value'     => 'tagTitle',
            'sort'      => 'tagTitle',
            'edit_link' => 'edit',
            'icon'      => 'core/tag',
        ]);

    $Listing->add_col([
            'title'     => 'Count',
            'value'     => 'tagCount',
            'sort'      => 'tagCount',
        ]);

    $Listing->add_delete_action([
            'inline' => true,
            'path'   => 'delete',
            'priv'   => 'assets.manage',
        ]);

    echo $Listing->render($tags);
