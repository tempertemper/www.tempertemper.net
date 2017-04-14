<?php
    $Settings->get('headerColour')->val();

    PerchUtil::set_security_headers();

    // Check for updates
    $update_setting_key = 'update_'.$Perch->version;
  	if (PERCH_RUNWAY) $update_setting_key = 'update_runway_'.$Perch->version;
    if (!$Settings->get($update_setting_key)->val()) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/update/');
    }

    // Help markup as used by apps etc
    $Perch->help_html = '';
    $help_html = '';

    header('Content-Type: text/html; charset=utf-8');

    if ($CurrentUser->logged_in()) {
    	include(PERCH_CORE . '/templates/layout/top.php');
    }else{
    	include(PERCH_CORE . '/templates/login/top.php');
    }