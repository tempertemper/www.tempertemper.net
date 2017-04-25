<?php
    if (defined('PERCH_SSL') && PERCH_SSL) {
        PerchSystem::force_ssl();
    }

    /* Check for auth plugins */
    if (defined('PERCH_AUTH_PLUGIN') && PERCH_AUTH_PLUGIN) {
        $plug_path = [PERCH_PATH, 'addons', 'plugins', 'auth', PERCH_AUTH_PLUGIN, 'auth.php'];
        require implode(DIRECTORY_SEPARATOR, $plug_path);
    }else{
        define('PERCH_AUTH_PLUGIN', false);
    }

    $Users       = new PerchUsers;
    $CurrentUser = $Users->get_current_user();
    
    /* Check for incoming login form and attempt login */
    $username = false;
    $password = false;
    if (isset($_POST['username']) && isset($_POST['password'])
        && $_POST['username']!='' && $_POST['password']!='') {
         $username   = $_POST['username'];
         $password   = $_POST['password']; 
    }
    
    if ($username!=false && $password!=false) {
        $auth_succeeded = $CurrentUser->authenticate($username, $password);
        if (!$auth_succeeded) {
            if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                header("HTTP/1.0 403 Forbidden", true, 403);
            }
        }
    }

    if (!isset($auth_page)) {
        $auth_page = false;
    }
    
    if (!$CurrentUser->logged_in() && !$auth_page) {
        PerchUtil::debug('Not logged in');
        $current_page = urlencode($Perch->get_page(true));
        PerchUtil::redirect(PERCH_LOGINPATH.'?r='.$current_page);
    }else{
        $Settings   = PerchSettings::fetch();
        $Settings->set_user($CurrentUser);
        if ($CurrentUser->logged_in()) {
            $Perch->find_installed_apps($CurrentUser);
        }
    }

    $Alert = new PerchAlert;