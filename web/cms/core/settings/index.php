<?php
    include(__DIR__ . '/../inc/pre_config.php');
    include(__DIR__ . '/../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');

    if (!$CurrentUser->has_priv('perch.settings')) {
        PerchUtil::redirect(PERCH_LOGINPATH);
    }
    
    $Perch->page_title = PerchLang::get('Settings');
    $Alert = new PerchAlert;
    
    include(PERCH_CORE . '/settings/modes/basic.pre.php');
    include(PERCH_CORE . '/settings/modes/_subnav.php');
    
    include(PERCH_CORE . '/inc/top.php');

    

    include(PERCH_CORE . '/settings/modes/basic.post.php');

    include(PERCH_CORE . '/inc/btm.php');