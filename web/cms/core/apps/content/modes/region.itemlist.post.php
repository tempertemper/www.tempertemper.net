<?php

    if ($Region->regionTemplate() != '') {
        $view_page_url = false;

        if ($Region->regionPage() != '*') {

            if ($Region->get_option('edit_mode')=='listdetail' && $Region->get_option('searchURL')!='') {
                $search_url = $Region->get_option('searchURL');  

                $details    = $Region->get_items_for_editing();
                $Region->tmp_url_vars = $details[0];             
                $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Region, 'substitute_url_vars'), $search_url);
                $Region->tmp_url_vars = false; 

                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$search_url;
            }else{
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Region->regionPage();
            }
        }

    } 

    echo $HTML->title_panel([
        'heading' => sprintf(PerchLang::get('Editing %s Region'),' &#8216;' . PerchUtil::html($Region->regionKey()) . '&#8217; '),
        'form' => [
            'action' => $Form->action(),
            'button' => $Form->submit('add_another', 'Add another item', 'button button-icon icon-left', true, true, PerchUI::icon('core/plus', 10))
        ]
    ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Breadcrumb
    $links = [];
    if ($Region->regionPage()=='*') {
        $links[] = [
            'title' => 'Regions',
            'link'  => '/core/apps/content/page/?id=-1',
        ];
    }else{
        $links[] = [
            'title' => 'Regions',
            'link'  => '/core/apps/content/page/?id='.$Region->pageID(),
        ];
    }

    if ($Region->regionMultiple() && $Region->get_option('edit_mode')=='listdetail') {
        $links[] = [
            'title' => $Region->regionKey(),
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id(),
        ];

    } else {
        $links[] = [
            'title' => $Region->regionKey(),
            'translate' => false,
            'link'  => '/core/apps/content/edit/?id='.$Region->id(),
        ];
    }

    $Smartbar->add_item([
            'active' => true,
            'type' => 'breadcrumb',
            'links' => $links,
        ]);
    
    // Region Options buttons
    $Smartbar->add_item([
            'active' => false,
            'title'  => 'Region Options',
            'link'   => '/core/apps/content/options/?id='.$Region->id(),
            'priv'   => 'content.regions.options',
            'icon'   => 'core/o-toggles',
        ]);

    if (PERCH_RUNWAY) {
         $Smartbar->add_item([
            'active' => false,
            'title'  => 'Revision History',
            'link'   => '/core/apps/content/revisions/?id='.$Region->id(),
            'priv'   => 'content.regions.options',
            'icon'   => 'core/o-backup',
            'position' => 'end',
        ]);

    }

    // View Page button
    if (isset($view_page_url) && $view_page_url) {
        $Smartbar->add_item([
                'active'        => false,
                'title'         => 'View Page',
                'link'          => $view_page_url,
                'link-absolute' => true,
                'position'      => 'end',
                'icon'          => 'core/o-world',
                'link-absolute' => true,
            ]);
    }

    // Reorder button
    if ($Region->regionMultiple()) {
        $Smartbar->add_item([
                'active'   => false,
                'title'    => 'Reorder',
                'link'     => '/core/apps/content/reorder/region/?id='.$Region->id(),
                'position' => 'end',
                'icon'     => 'core/menu',
            ]);
    } 


    echo $Smartbar->render();

if (PerchUtil::count($items)) {    
    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $first = true;
    foreach($cols as $col) {
        $i = 1;
        $Listing->add_col([
                'title'     => $col['title'],
                'sort'      => $col['id'],
                'value'     => function($item) use ($col, $first, $i) {
                    $item = $item->to_array();
                    if ($col['id']=='_title') {
                        if (isset($item['_title'])) {
                            $title = $item['_title'];
                        }else{
                            $title = PerchLang::get('Item').' '.((int)$item['itemOrder']-999);
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
                            $Template = new PerchTemplate;
                            $title = $Template->format_value($col['Tag'], $title);
                        }
                    }

                    if ($first && trim($title)=='') {
                        $title = '#'.$item['_id'];
                    }

                    return $title;

                },
                'edit_link' => ($first ? '?id='.$Region->id().'&itm=' : false),
            ]);

        $first = false;
        $i++;
    }


        

        $Listing->add_delete_action([
                'inline' => true,
                'path'   => '../delete/item/?id='.$Region->id().'&itm=',
                'custom' => true,
            ]);


    echo $Listing->render($Listing->objectify($items, 'itemID'));
}