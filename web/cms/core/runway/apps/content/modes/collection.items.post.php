<?php
    echo $HTML->title_panel([
        'heading' => sprintf($Lang->get('Editing %s Collection'),' &#8216;' . PerchUtil::html($Collection->collectionKey()) . '&#8217; '),
        'form' => [
            'action' => $Form->action(),
            'button' => $Form->submit('add_another', 'Add another item', 'button button-icon icon-left', true, true, PerchUI::icon('core/plus', 10))
        ]
    ]);



    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Breadcrumb
    $links = [];

    $links[] = [
        'title' => 'Collections',
        'link'  => '/core/apps/content/manage/collections/',
    ];

    $links[] = [
        'title' => $Collection->collectionKey(),
        'translate' => false,
        'link'  => '/core/apps/content/collections/?id='.$Collection->id(),
    ];

    $Smartbar->add_item([
            'active' => true,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Options button
    $Smartbar->add_item([
            'active' => false,
            'title'  => 'Options',
            'link'   => '/core/apps/content/collections/options/?id='.$Collection->id(),
            'priv'   => 'content.collections.options',
            'icon'   => 'core/o-toggles',
        ]);


    // Revision history
    /*
    $Smartbar->add_item([
        'active' => false,
        'title'  => 'Revision History',
        'link'   => '/core/apps/content/collections/revisions/?id='.$Collection->id(),
        'priv'   => 'content.regions.options',
        'icon'   => 'core/o-backup',
        'position' => 'end',
    ]);
    */


    // Import button
    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Import',
            'link'     => '/core/apps/content/collections/import/?id='.$Collection->id(),
            'position' => 'end',
            'icon'     => 'core/inbox-download',
        ]);


    // Reorder button    
    $Smartbar->add_item([
            'active'   => false,
            'title'    => 'Reorder',
            'link'     => '/core/apps/content/reorder/collection/?id='.$Collection->id(),
            'position' => 'end',
            'icon'     => 'core/menu',
        ]);




    echo $Smartbar->render();



    if (PerchUtil::count($items)) {    
        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
        $first = true;
        $i = 0;
        foreach($cols as $col) {
            $Listing->add_col([
                    'title'     => $col['title'],
                    'sort'      => $col['id'],
                    'value'     => function($item) use ($col, $first, &$i) {
                        $item = $item->to_array();
                        if ($col['id']=='_title') {
                            if (isset($item['_title'])) {
                                $title = $item['_title'];
                            }else{
                                $i++;
                                $title = PerchLang::get('Item').' '.$i;
                            }
                        }else{
                            if (isset($item[$col['id']])) {
                                $title = $item[$col['id']];
                            }else{
                                if ($first) {
                                    if (isset($item['_title'])) {
                                        $title = $item['_title'];
                                    }else{
                                        $i++;
                                        $title = PerchLang::get('Item').' '.$i;
                                    }
                                }else{
                                    $title = '-';
                                }
                            }

                    }

                        if ($col['Tag']) {

                            $FieldType = PerchFieldTypes::get($col['Tag']->type(), false, $col['Tag']);

                            $title = $FieldType->render_admin_listing($title);

                            if ($col['Tag']->format()) {
                                $Template = new PerchTemplate;
                                $title = $Template->format_value($col['Tag'], $title);
                            }
                        }

                        if ($first && trim($title)=='') {
                            $title = '#'.$item['_id'];
                        }

                        return $title;

                    },
                    'edit_link' => ($first ? PERCH_LOGINPATH.'/core/apps/content/collections/edit/?id='.$Collection->id().'&itm=' : false),
                ]);

            $first = false;
        }


            

            $Listing->add_delete_action([
                    'inline' => true,
                    'path'   => PERCH_LOGINPATH.'/core/apps/content/delete/collection/item/?id=' . $Collection->id() . '&itm=',
                    'custom' => true,
                ]);


        echo $Listing->render($Listing->objectify($items, 'itemID'));
    }





        if (PerchUtil::count(false && $items)) {
            
            echo '<table class="d itemlist">';
                echo '<thead>';
                    echo '<tr>';
                        foreach($cols as $col) {
                            echo '<th>'.PerchUtil::html($col['title']).'</th>';
                        }
                        echo '<th class="last action"></th>';
                    echo '</tr>';
                echo '</thead>';
            
                echo '<tbody>';
                $Template = new PerchTemplate;
                $i = 1;
                foreach($items as $item) {
                    echo '<tr>';
                        $first = true;
                        foreach($cols as $col) {

                            if ($first) { 
                                echo '<td class="primary">';
                                if ($item['_has_draft']) echo '<span class="draft icon" title="'.PerchLang::get('This item is a draft.').'"></span>';
                                echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/collections/edit/?id=' . PerchUtil::html($Collection->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'">';
                            }else{
                                echo '<td>';
                            }

                            if ($col['id']=='_title') {
                                if (isset($item['_title'])) {
                                    $title = $item['_title'];
                                }else{
                                    $title = PerchLang::get('Item').' '.$i;
                                }
                            }else{
                                if (isset($item[$col['id']])) {
                                    $title = $item[$col['id']];    
                                }else{
                                    if ($first) {
                                        if (isset($item['_title'])) {
                                            $title = $item['_title'];
                                        }else{
                                            $title = PerchLang::get('Item').' '.$i;
                                        }
                                    }else{
                                        $title = '-';
                                    }
                                }
                                
                            }

                            if ($col['Tag']) {

                                $FieldType = PerchFieldTypes::get($col['Tag']->type(), false, $col['Tag']);

                                $title = $FieldType->render_admin_listing($title);

                                if ($col['Tag']->format()) {
                                    $title = $Template->format_value($col['Tag'], $title);
                                }
                            }
                            
                            if ($first && trim($title)=='') $title = '#'.$item['_id'];

                            echo $title;

                            if ($first) echo '</a>';
                             
                            echo '</td>';

                            $first = false;
                        }
                        echo '<td>';
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/delete/collection/item/?id=' . PerchUtil::html($Collection->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="delete inline-delete">'.PerchLang::get('Delete').'</a>';
                        echo '</td>';
                    echo '</tr>';
                    $i++;
                }
                echo '</tbody>';
            
            
            echo '</table>';
            
    
            if ($Paging->enabled()) {
                $API = new PerchAPI(1.0, 'perch_content');
                $HTML = $API->get('HTML');
                echo $HTML->paging($Paging);
            }

        }
    


