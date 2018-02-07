<?php
    include(realpath(__DIR__ . '/../../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth_light.php');

    header('X-Perch: '.$Perch->version);
    header('Content-Type: text/html; charset=utf-8');

    if (!class_exists('PerchAssets_Asset')) {
        include(__DIR__.'/../../PerchAssets_Asset.class.php');
        include(__DIR__.'/../../PerchAssets_Assets.class.php');
        include(__DIR__.'/../../PerchAssets_Tags.class.php');
        include(__DIR__.'/../../PerchAssets_Tag.class.php');    
    }

    $Paging = new PerchPaging();
    $Paging->set_per_page(64);

    $API  = new PerchAPI(1.0, 'assets');
    $HTML = $API->get('HTML');
    $Lang = $API->get('Lang');

    $Assets = new PerchAssets_Assets;

    if (isset($_GET['q']) && $_GET['q']!='') {
        $term = $_GET['q'];
    }

    if (isset($_GET['buckets']) && $_GET['buckets']!='') {
        $template_buckets = explode(' ', urldecode($_GET['buckets']));
        
        $template_buckets = $Assets->hydrate_bucket_list($template_buckets, $CurrentUser);
    } else {
        $template_buckets = false;
    }

    include(__DIR__.'/../../modes/_smart_bar.php');

    //PerchUtil::output_debug();