<?php
    include(__DIR__ . '/../inc/pre_config.php');
    include(__DIR__ . '/../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');

    
    $Perch->page_title = PerchLang::get('Dashboard');
    $Alert = new PerchAlert;
    
    include(PERCH_CORE . '/dashboard/modes/_subnav.php');
    
    include(PERCH_CORE . '/dashboard/modes/dash.pre.php');
    
    if ($CurrentUser->logged_in()) $Perch->find_installed_apps($CurrentUser);
    
    include(PERCH_CORE . '/inc/top.php');

    include(PERCH_CORE . '/dashboard/modes/dash.post.php');

    include(PERCH_CORE . '/inc/btm.php');