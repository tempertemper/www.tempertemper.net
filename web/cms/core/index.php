<?php
    $auth_page = true;

    include(__DIR__ . '/inc/pre_config.php');
    include(__DIR__ . '/../config/config.php');

    if (!defined('PERCH_PATH')) {
        header('Location: setup');
        exit;
    }

    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    // Check for logout
    if ($CurrentUser->logged_in() && isset($_GET['logout']) && is_numeric($_GET['logout'])) {
        $CurrentUser->logout();
    }

    // If the user's logged in, send them to edit content
    if ($CurrentUser->logged_in()) {

        if (isset($_POST['r']) && $_POST['r']!='') {
            $redirect_url = urldecode(base64_decode($_POST['r']));
            $r = parse_url($redirect_url);
            if (isset($r['path'])) {
                PerchUtil::redirect($redirect_url);
            }
        }

        if ($Settings->get('dashboard')->settingValue()) {
            PerchUtil::redirect(PERCH_LOGINPATH . '/core/dashboard/');
        }
        
        $apps = $Perch->get_apps();
        if (PerchUtil::count($apps)) {
            PerchUtil::redirect($apps[0]['path']);
        }   
    }

    $Perch->page_title = PerchLang::get('Log in');
    include(PERCH_CORE . '/inc/top.php');
    include(PERCH_CORE . '/templates/login/login.php');
    include(PERCH_CORE . '/inc/btm.php');
