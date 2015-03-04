<?php
    include(realpath(__DIR__ . '/../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth_light.php');
    
    header('X-Perch: '.$Perch->version);
    header('Content-Type: text/html; charset=utf-8');
    
    include(__DIR__.'/../PerchAssets_Asset.class.php');
    include(__DIR__.'/../PerchAssets_Assets.class.php');

    $Assets = new PerchAssets_Assets;

    $filter       = 'all';
    $filter_value = '';
    $view         = 'grid';
    $term         = false;


    if (isset($_GET['filter']) && $_GET['filter']=='new') {
        $filter = 'new';
    }
    
    if (isset($_GET['bucket']) && $_GET['bucket']!='') {
        $filter = 'bucket';
        $filter_value = $_GET['bucket'];
    }

    if (isset($_GET['app']) && $_GET['app']!='') {
        $filter = 'app';
        $filter_value = $_GET['app'];
    }

    if (isset($_GET['type']) && $_GET['type']!='') {
        $filter = 'type';
        $filter_value = $_GET['type'];
    }

    if (isset($_GET['date']) && $_GET['date']!='') {
        $filter = 'date';
        $filter_value = $_GET['date'];
    }

    if (isset($_GET['view']) && $_GET['view']!='') {
        if ($_GET['view']=='list') {
            $view = 'list';
        }else{
            $view = 'grid';
        }
    }

    if (isset($_GET['q']) && $_GET['q']!='') {
        $term = $_GET['q'];
    }

    $base_path = '';

    include(__DIR__.'/../modes/_smart_bar.php');