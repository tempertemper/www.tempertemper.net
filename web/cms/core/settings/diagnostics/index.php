<?php
    include(__DIR__ . '/../../inc/pre_config.php');
    include(__DIR__ . '/../../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');

    if (!$CurrentUser->has_priv('perch.settings')) {
        PerchUtil::redirect(PERCH_LOGINPATH);
    }
  
    
    $Perch->page_title = PerchLang::get('Settings');
    $Alert = new PerchAlert;


    if (isset($_GET['extended'])) {
        include(__DIR__ . '/../modes/diagnostics.extended.pre.php');
    }else{
        include(__DIR__ . '/../modes/diagnostics.pre.php');
    }
    
    include(__DIR__ . '/../modes/_subnav.php');
    
    include(PERCH_CORE . '/inc/top.php');

     if (isset($_GET['extended'])) {
         include(__DIR__ . '/../modes/diagnostics.extended.post.php');
     }else{
         include(__DIR__ . '/../modes/diagnostics.post.php');
     }

    include(PERCH_CORE . '/inc/btm.php');
