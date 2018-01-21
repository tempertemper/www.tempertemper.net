<?php
    include(realpath(__DIR__ . '/../../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth_light.php');
    
    $Perch->page_title = PerchLang::get('Manage Assets');

    include(__DIR__.'/../../PerchAssets_Asset.class.php');
    include(__DIR__.'/../../PerchAssets_Assets.class.php');
    include(__DIR__.'/../../PerchAssets_Tags.class.php');
    include(__DIR__.'/../../PerchAssets_Tag.class.php');

    $Paging = new PerchPaging();
    $Paging->set_per_page(64);

    $Assets = new PerchAssets_Assets;
    $Settings = PerchSettings::fetch();

    $view         = 'grid';
    $filters = array();
        
    if (isset($_GET['filter']) && $_GET['filter']=='new') {
        $filters['new'] = true;
    }
    
    if (isset($_GET['app']) && $_GET['app']!='') {
        $filters['app'] = $_GET['app'];
    }

    if (isset($_GET['type']) && $_GET['type']!='') {
        $filters['type'] = $_GET['type'];
    }

    if (isset($_GET['bucket']) && $_GET['bucket']!='') {
        $filters['bucket'] = $_GET['bucket'];
    }

    if (isset($_GET['date']) && $_GET['date']!='') {
        $filters['date'] = $_GET['date'];
    }

    if (isset($_GET['tag']) && $_GET['tag']!='') {
        $filters['tag'] = $_GET['tag'];
    }

    if (isset($_GET['buckets']) && $_GET['buckets']!='') {
        $template_buckets = explode(' ', $_GET['buckets']);
        $template_buckets = $Assets->hydrate_bucket_list($template_buckets, $CurrentUser);
    } else {
        $template_buckets = false;
    }

    if (!$Settings->get('assets_restrict_buckets')->val()) {
        if (isset($filters['bucket'])) {
            $template_buckets[] = $filters['bucket'];
        }
    }


    if (isset($_GET['q']) && $_GET['q']!='') {
        $term = $_GET['q'];
        $assets = $Assets->search($term, $filters, $CurrentUser, $template_buckets);
    }else{
        $assets = $Assets->get_filtered_for_admin($Paging, $filters, $CurrentUser, $template_buckets); 
    }



    $out = array();


    if (PerchUtil::count($assets)) {

        foreach($assets as $Asset) {
            $out[] = $Asset->to_api_array();
        }

    }

    echo PerchUtil::json_safe_encode(array('assets'=>$out));
