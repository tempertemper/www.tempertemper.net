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
    if (!$CurrentUser->logged_in()) die();
    
    $Alert = new PerchAlert;

