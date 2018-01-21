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
        'heading' => sprintf(PerchLang::get('Editing %s Region'),' &#8216;' . PerchUtil::html($Region->regionKey()) . '&#8217; ')
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
            'active' => false,
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
            'active' => true,
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


    if (PerchUtil::count($revisions)) {

        $Users = new PerchUsers;

        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
        $Revisions = $Listing->objectify($revisions, 'itemRev');

        $Listing->add_col([
            'title'     => 'Revision',
            'value'     => function($Item) use ($Region, $Lang) {
                if ($Item->itemRev()==$Region->regionRev()) {
                    return '<span class="badge success revision-published" title="'.$Lang->get('Published revision').'">'.$Item->itemRev().'</span>';
                }elseif ($Item->itemRev()==$Region->regionLatestRev() && $Region->regionLatestRev()>$Region->regionRev()) {
                    return '<span class="badge warning revision-draft" title="'.$Lang->get('Unpublished draft').'">'.$Item->itemRev().'</span>';
                }else{
                    return $Item->itemRev();
                }
            },
        ]);

        $Listing->add_col([
            'title'     => 'Date',
            'value'     => function($Item) use ($Lang) {
                if ($Item->itemUpdated()!='0000-00-00 00:00:00') {
                    return strftime(PERCH_DATE_LONG.' '.PERCH_TIME_SHORT, strtotime($Item->itemUpdated()));
                }else{
                    return '<span class="minor-note">'.$Lang->get('Not logged').'</span>';
                }
            },
        ]);

        $Listing->add_col([
            'title'     => 'Created by',
            'value'     => function($Item) use ($Users) {
                return $Users->get_user_display_name($Item->itemUpdatedBy());
            },
        ]);

        if ($preview_url) {
            $Listing->add_misc_action([
                'title'     => 'Preview',
                'new-tab'   => true,
                'class'     => 'warning',
                'path'     => function($Item) use ($preview_url) {
                    return $preview_url.$Item->itemRev();
                },
            ]);
        }

        $Listing->add_misc_action([
                'title'     => 'Roll back',
                'class'     => 'success',
                'path'     => function($Item) use ($Region) {
                    return PERCH_LOGINPATH.'/core/apps/content/revisions/revert/?id='.$Region->id().'&rev='.$Item->itemRev();
                },
                'display' => function($Item) use ($Region) {
                    if ($Item->itemRev() < $Region->regionRev()) return true;
                    return false;
                }
            ]);

        echo $Listing->render($Revisions);
        
        
    }
    
