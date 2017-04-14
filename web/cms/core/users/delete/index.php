<?php
    include(__DIR__ . '/../../inc/pre_config.php');
    include(__DIR__ . '/../../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');

    if (!$CurrentUser->has_priv('perch.users.manage')) {
        PerchUtil::redirect(PERCH_LOGINPATH);
    }
    
    
    $Perch->page_title = PerchLang::get('Manage Users');
    $Alert = new PerchAlert;
    
    include(PERCH_CORE . '/users/modes/delete.pre.php');
    include(PERCH_CORE . '/users/modes/_subnav.php');
    
    include(PERCH_CORE . '/inc/top.php');

    include(PERCH_CORE . '/users/modes/delete.post.php');

    include(PERCH_CORE . '/inc/btm.php');

