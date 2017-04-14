<?php

    echo $HTML->title_panel([
        'heading' => $Lang->get('Listing blogs'),
        'button'  => [
                        'text' => $Lang->get('Add blog'),
                        'link' => $API->app_nav().'/blogs/edit/',
                        'icon' => 'core/plus',
                        'priv' => 'perch_blog.blog.create',
                    ]
        ], $CurrentUser);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title' => $Lang->get('Blogs'),
        'link'  => $API->app_nav().'/blogs/',
        'icon'  => 'blocks/newspaper',
    ]);

    echo $Smartbar->render();


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Blog',
            'value'     => 'blogTitle',
            'sort'      => 'blogTitle',
            'edit_link' => 'edit',
        ]);

    $Listing->add_col([
            'title'     => 'Slug',
            'value'     => 'blogSlug',
            'sort'      => 'blogSlug',
        ]);

    $Listing->add_col([
            'title'     => 'Posts',
            'value'     => 'blogPostCount',
        ]);

    $Listing->add_delete_action([
            'priv'   => 'perch_blog.blog.delete',
            'inline' => true,
            'path'   => 'delete',
        ]);

    echo $Listing->render($blogs);
