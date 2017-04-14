<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Listing posts'),
            'button'  => [
                            'text' => $Lang->get('Add post'),
                            'link' => $API->app_nav().'/edit/'.(PERCH_RUNWAY ? '?blog='.$Blog->id() : ''),
                            'icon' => 'core/plus',
                            'priv' => 'perch_blog.post.create',
                        ]
            ], $CurrentUser);




    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    if (PerchUtil::count($blogs)) {
        foreach($blogs as $Item) {

            $Smartbar->add_item([
                'active' => ($Item->id()==$Blog->id()),
                'title' => $Item->blogTitle(),
                'link'  => $API->app_nav().'/?blog='.$Item->blogSlug(),
                'icon'  => 'blocks/newspaper',
            ]);

        }
    }

    if (PerchUtil::count($categories)) {

        $category_options = [];

        foreach($categories as $Category) {
            $category_options[] = [
                            'value' => $Category->catPath(),
                            'title' => $Category->catTitle(),
                        ];
        }

        $Smartbar->add_item([
                    'id'      => 'cf',
                    'title'   => $Lang->get('By Category'),
                    'icon'    => 'core/o-connect',
                    'active'  => PerchRequest::get('category'),
                    'type'    => 'filter',
                    'arg'     => 'category',
                    'persist' => ['blog'],
                    'options' => $category_options,
                    'actions' => [
                                [
                                    'title'  => 'Clear',
                                    'remove' => ['category', 'show-filter'],
                                    'icon'   => 'core/cancel',
                                ]
                            ],
                    ]);
    }

    if (PerchUtil::count($sections) > 1) {

        $section_options = [];

        foreach($sections as $Section) {
            $section_options[] = [
                            'value' => $Section->sectionSlug(),
                            'title' => $Section->sectionTitle(),
                        ];
        }

        $Smartbar->add_item([
                    'id'      => 'sf',
                    'title'   => $Lang->get('By Section'),
                    'icon'    => 'core/grid-big',
                    'active'  => PerchRequest::get('section'),
                    'type'    => 'filter',
                    'arg'     => 'section',
                    'persist' => ['blog'],
                    'options' => $section_options,
                    'actions' => [
                                [
                                    'title'  => 'Clear',
                                    'remove' => ['section', 'show-filter'],
                                    'icon'   => 'core/cancel',
                                ]
                            ],
                    ]);
    }

    $Smartbar->add_item([
                'active' => false,
                'title' => $Lang->get('Import'),
                'link'  => $API->app_nav().'/import/',
                'icon'  => 'core/inbox-download',
                'position' => 'end',
            ]);


    echo $Smartbar->render();

    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Post',
            'value'     => 'postTitle',
            'sort'      => 'postTitle',
            'edit_link' => 'edit',
        ]);

    $Listing->add_col([
            'title'     => 'Status',
            'sort'      => 'postStatus',
            'value'     => function($Post) use ($Lang, $HTML) {
                if (strtotime($Post->postDateTime()) > time() && $Post->postStatus()=='Published') {
                        return $Lang->get('Will publish on date');
                }else{
                    if ($Post->postStatus()=='Draft') {
                        return $HTML->encode($Lang->get($Post->postStatus()));
                    }else{
                        return $HTML->encode($Lang->get($Post->postStatus()));
                    }
                }
            },
        ]);

    $Listing->add_col([
            'title'     => 'Date',
            'sort'      => 'postDateTime',
            'value'     => function($Post) {
                return strftime(PERCH_DATE_LONG.', '.PERCH_TIME_SHORT, strtotime($Post->postDateTime()));
            },
        ]);

    $Listing->add_delete_action([
            'priv'   => 'perch_blog.post.delete',
            'inline' => true,
            'path'   => 'delete',
        ]);

    echo $Listing->render($posts);
