<?php
    include(__DIR__ . '/../../inc/pre_config.php');
    include(__DIR__ . '/../../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    $Perch->page_title = PerchLang::get('Search');
    $Alert = new PerchAlert;
    
    
    include(PERCH_CORE.'/runway/search/modes/_subnav.php');
    include(PERCH_CORE.'/runway/search/modes/result.pre.php');
    
    include(PERCH_CORE . '/inc/top.php');
    include(PERCH_CORE.'/runway/search/modes/result.post.php');
    include(PERCH_CORE . '/inc/btm.php');
