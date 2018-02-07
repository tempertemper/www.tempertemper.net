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
    $Paging->set_per_page(24);

    $Assets = new PerchAssets_Assets;
      
    if (isset($_GET['q']) && $_GET['q']!='') {
        $term = $_GET['q'];
    }else{
        die();
    }

    $assets = $Assets->search($term, false, $CurrentUser);           

    $out = array();

    if (PerchUtil::count($assets)) {
        foreach($assets as $Asset) {
            $out[] = $Asset->to_api_array();
        }

    }

    echo PerchUtil::json_safe_encode(array('assets'=>$out));
