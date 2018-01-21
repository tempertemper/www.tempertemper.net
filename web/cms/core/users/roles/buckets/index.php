<?php
    include(__DIR__ . '/../../../inc/pre_config.php');
    include(__DIR__ . '/../../../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');

    if (!$CurrentUser->has_priv('perch.users.manage')) {
        PerchUtil::redirect(PERCH_LOGINPATH);
    }

    if (!PERCH_RUNWAY) {
        PerchUtil::redirect(PERCH_LOGINPATH);
    }

    
    
    $Perch->page_title = PerchLang::get('Manage Roles - Buckets');
    $Alert = new PerchAlert;
    
    include(PERCH_CORE . '/users/modes/_subnav.php');
    include(PERCH_CORE . '/users/modes/roles.buckets.pre.php');
    
    include(PERCH_CORE . '/inc/top.php');

    include(PERCH_CORE . '/users/modes/roles.buckets.post.php');

    include(PERCH_CORE . '/inc/btm.php');
