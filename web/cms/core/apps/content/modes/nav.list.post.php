<?php
    echo $HTML->title_panel([
            'heading' => $Lang->get('Listing all navigation groups'),
            'button'  => [
                            'text' => $Lang->get('Add group'),
                            'link' => '/core/apps/content/navigation/edit/',
                            'icon' => 'core/plus',
                            'priv' => 'content.navgroups.create',
                        ]
            ], $CurrentUser);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title' => 'Navigation groups',
        'link'  => '/core/apps/content/navigation/',
    ]);


    echo $Smartbar->render();


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Title',
            'value'     => 'groupTitle',
            'sort'      => 'groupTitle',
            'edit_link' => 'pages',
        ]);
    $Listing->add_col([
            'title'     => 'Slug',
            'value'     => 'groupSlug',
            'sort'      => 'groupSlug',
        ]);
    $Listing->add_delete_action([
            'priv'   => 'content.navgroups.delete',
            'inline' => true,
            'path'   => 'delete',
        ]);

    echo $Listing->render($groups);
