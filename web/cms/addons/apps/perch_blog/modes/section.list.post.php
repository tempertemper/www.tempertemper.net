<?php
   echo $HTML->title_panel([
            'heading' => $Lang->get('Listing sections'),
            'button'  => [
                            'text' => $Lang->get('Add section'),
                            'link' => $API->app_nav().'/sections/edit/'.(PERCH_RUNWAY ? '?blog='.$Blog->id() : ''),
                            'icon' => 'core/plus',
                            'priv' => 'perch_blog.sections.create',
                        ]
            ], $CurrentUser);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    if (PerchUtil::count($blogs)) {
        foreach($blogs as $Item) {

            $Smartbar->add_item([
                'active' => ($Item->id()==$Blog->id()),
                'title' => $Item->blogTitle(),
                'link'  => $API->app_nav().'/sections/?blog='.$Item->blogSlug(),
                'icon'  => 'blocks/newspaper',
            ]);

        }
    }

    echo $Smartbar->render();

    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Section',
            'value'     => 'sectionTitle',
            'sort'      => 'sectionTitle',
            'edit_link' => 'edit',
        ]);

    $Listing->add_col([
            'title'     => 'Slug',
            'value'     => 'sectionSlug',
            'sort'      => 'sectionSlug',
        ]);

    $Listing->add_col([
            'title'     => 'Posts',
            'value'     => 'sectionPostCount',
        ]);

    $Listing->add_delete_action([
            'priv'   => 'perch_blog.post.delete',
            'inline' => true,
            'path'   => 'delete',
        ]);

    echo $Listing->render($sections);
