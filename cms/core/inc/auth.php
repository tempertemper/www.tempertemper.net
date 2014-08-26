<?php
    if (defined('PERCH_SSL') && PERCH_SSL) {
        PerchSystem::force_ssl();
    }

    /* Check for auth plugins */
    if (defined('PERCH_AUTH_PLUGIN')) {
        require PERCH_PATH.DIRECTORY_SEPARATOR.'addons'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'auth'.DIRECTORY_SEPARATOR.PERCH_AUTH_PLUGIN.DIRECTORY_SEPARATOR.'auth.php';
    }else{
        define('PERCH_AUTH_PLUGIN', false);
    }

    $Users  = new PerchUsers;
    $CurrentUser   = $Users->get_current_user();
    
    /* Check for incoming login form and attempt login */
    $username = false;
    $password = false;
    if (isset($_POST['username']) && isset($_POST['password'])
        && $_POST['username']!='' && $_POST['password']!='') {
         $username   = $_POST['username'];
         $password   = $_POST['password']; 
    }
    
    if ($username!=false && $password!=false) {
        $CurrentUser->authenticate($username, $password);
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
        
        $Perch->find_installed_apps($CurrentUser);
    }
    

    $Alert = new PerchAlert;
    