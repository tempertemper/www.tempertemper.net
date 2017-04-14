<?php
    include(realpath(__DIR__ . '/../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    $Perch->page_title = PerchLang::get('Manage Content');

    $app_path = __DIR__;
    
    include(__DIR__.'/modes/list.pre.php');
    include(__DIR__.'/modes/_subnav.php');
    
    include(PERCH_CORE.'/inc/top.php');

    include(__DIR__.'/modes/list.post.php');

    include(PERCH_CORE.'/inc/btm.php');
