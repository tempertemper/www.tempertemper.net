<?php
    include(__DIR__ . '/../../inc/pre_config.php');
    include(__DIR__ . '/../../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');

    if (!$CurrentUser->has_priv('perch.settings')) {
        PerchUtil::redirect(PERCH_LOGINPATH);
    }

    
    
    $Perch->page_title = PerchLang::get('Settings - Scheduled Tasks');
    $Alert = new PerchAlert;
    
    include(__DIR__ . '/../modes/tasks.pre.php');
    include(__DIR__ . '/../modes/_subnav.php');
    
    include(PERCH_CORE . '/inc/top.php');

    include(__DIR__ . '/../modes/tasks.post.php');

    include(PERCH_CORE . '/inc/btm.php');
