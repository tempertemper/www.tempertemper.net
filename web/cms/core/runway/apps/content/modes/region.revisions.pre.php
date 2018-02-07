<?php
    $API    = new PerchAPI(1.0, 'content');
    $HTML   = $API->get('HTML');
    $Lang   = $API->get('Lang');
    $Paging = $API->get('Paging');


    $Regions = new PerchContent_Regions;
    $Region  = false;
    
    // Find the region
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Region = $Regions->find($id);
    }
    
    // Check we have a region
    if (!$Region || !is_object($Region)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }
    
    // Check permissions
    if (!$Region->role_may_edit($CurrentUser)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/edit/denied/');
    }
    
    $Pages = new PerchContent_Pages;
    $Page = $Pages->find($Region->pageID());

    if (!is_object($Page)) {
        $Page = $Pages->get_mock_shared_page();
    }

    $revisions = $Region->get_revisions();

    $path = rtrim($Settings->get('siteURL')->val(), '/');

    $preview_url = false;

    if ($Region->get_option('edit_mode')=='listdetail' && $Region->get_option('searchURL')!='') {
        $search_url = $Region->get_option('searchURL');  

        $details    = $Region->get_items_for_editing();
        $Region->tmp_url_vars = $details[0];             
        $search_url = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', array($Region, 'substitute_url_vars'), $search_url);
        $Region->tmp_url_vars = false; 

        if (strpos($search_url, '?')!==false) {
            $preview_url = $search_url . '&' . PERCH_PREVIEW_ARG.'='.$Region->id().'r';
        }else{
            $preview_url = $search_url . '?' . PERCH_PREVIEW_ARG.'='.$Region->id().'r';
        }

    }else{
        $preview_url = $path.$Region->regionPage().'?'.PERCH_PREVIEW_ARG.'='.$Region->id().'r';
    }