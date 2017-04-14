<?php
    include(realpath(__DIR__ . '/../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    $Perch->page_title = PerchLang::get('Reindex Assets');


            
    include(__DIR__.'/../modes/_subnav.php');
    include(__DIR__.'/../modes/reindex.pre.php');
   
    include(PERCH_CORE.'/inc/top.php');

    include(__DIR__.'/../modes/reindex.post.php');

    include(PERCH_CORE.'/inc/btm.php');
