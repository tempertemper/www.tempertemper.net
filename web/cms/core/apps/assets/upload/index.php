<?php
    $ajax = false;

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest') {
        $ajax = true;
        # if (PERCH_DEBUG) sleep(5);
    }

    include(realpath(__DIR__ . '/../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    if (!$ajax) $Perch->page_title = PerchLang::get('Upload Assets');

    include(__DIR__.'/../PerchAssets_Asset.class.php');
    include(__DIR__.'/../PerchAssets_Assets.class.php');
    include(__DIR__.'/../PerchAssets_Display.class.php');
    include(__DIR__.'/../PerchAssets_Tags.class.php');
    include(__DIR__.'/../PerchAssets_Tag.class.php');
            
    include(__DIR__.'/../modes/upload.pre.php');
   
    if (!$ajax) include(PERCH_CORE.'/inc/top.php');

    if (!$ajax) include(__DIR__.'/../modes/upload.post.php');

    if (!$ajax) include(PERCH_CORE.'/inc/btm.php');

    //file_put_contents('log.txt', strip_tags(PerchUtil::output_debug(1)));
