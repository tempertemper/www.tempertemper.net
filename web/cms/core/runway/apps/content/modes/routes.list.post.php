<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Listing Page Routes'),
            ], $CurrentUser);

        $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
        $Smartbar->add_item([
                'active' => true,
                'title'  => 'Routes',
                'link'   => '/core/apps/content/routes/',
                'icon'   => 'core/o-signs',
            ]);
        $Smartbar->add_item([
                'active'   => false,
                'title'    => 'Reorder',
                'link'     => '/core/apps/content/routes/reorder/',
                'position' => 'end',
                'icon'   => 'core/menu',
            ]);
        echo $Smartbar->render();


        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
        $Listing->add_col([
                'title'     => 'Pattern',
                'value'     => function($Item) use ($HTML) {
                    return $HTML->wrap('code', $Item->routePattern());
                },
            ]);
        $Listing->add_col([
                'title'     => 'Page',
                'value'     => function($item) use ($HTML) {
                    if ($item->pageID() == '0') {
                        return $HTML->wrap('a[href='.PERCH_LOGINPATH.'/core/apps/content/page/templates/edit/?id='.$item->templateID().']', PerchUI::icon('core/o-documents', 12). ' '. $item->templateTitle());
                    } else {

                        return $HTML->wrap('a[href='.PERCH_LOGINPATH.'/core/apps/content/page/url/?id='.$item->pageID().']', $item->pagePath());
                    }
                }
            ]);        
        $Listing->add_col([
                'title'     => 'Order',
                'value'     => 'routeOrder',
            ]);
        $Listing->add_delete_action([
                'inline' => true,
                'path'   => 'delete',
            ]);

        echo $Listing->render($routes);
