<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Listing authors'),
            ], $CurrentUser);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title' => $Lang->get('Authors'),
        'link'  => $API->app_nav().'/authors/',
        'icon'  => 'core/users',
    ]);

    echo $Smartbar->render();

    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Name',
            'value'     => function($Author) use ($HTML) {
                return $HTML->encode($Author->authorGivenName().' '.$Author->authorFamilyName());
            },
            'sort'      => 'authorFamilyName',
            'edit_link' => 'edit',
        ]);

    $Listing->add_col([
            'title'     => 'Email',
            'value'     => 'authorEmail',
            'sort'      => 'authorEmail',
        ]);

    $Listing->add_col([
            'title'     => 'Posts',
            'value'     => 'authorPostCount',
            'sort'      => 'authorPostCount',
        ]);
    

    $Listing->add_delete_action([
            'priv'   => 'perch_blog.post.delete',
            'inline' => true,
            'path'   => 'delete',
        ]);

    echo $Listing->render($authors);
