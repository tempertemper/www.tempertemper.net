<?php
    include(realpath(__DIR__ . '/../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    $Perch->page_title = PerchLang::get('Manage Assets');

    include(__DIR__.'/PerchAssets_Asset.class.php');
    include(__DIR__.'/PerchAssets_Assets.class.php');
    include(__DIR__.'/PerchAssets_Tags.class.php');
    include(__DIR__.'/PerchAssets_Tag.class.php');
    include(__DIR__.'/PerchAssets_Display.class.php');
            
    include(__DIR__.'/modes/list.pre.php');
    include(__DIR__.'/modes/_subnav.php');
   
    include(PERCH_CORE.'/inc/top.php');


    include(__DIR__.'/modes/list.post.php');

    include(PERCH_CORE.'/inc/btm.php');
