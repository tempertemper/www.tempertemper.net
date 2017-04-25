<?php
	if (!defined('PERCH_PATH')) exit;

	$headerColour = $Settings->get('headerColour')->val();
	$hideBranding = $Settings->get('hideBranding')->val();
	$lang  		  = $Settings->get('lang')->settingValue();

	$db_error = true;
	if ($headerColour) $db_error = false;


	// Page title
	$page_title = $Perch->page_title;

	if (!$hideBranding) {
    	if (PERCH_RUNWAY) {
    		$page_title .= ' - ' . PerchLang::get('Perch Runway');
    	}else{
    		$page_title .= ' - ' . PerchLang::get('Perch');
    	}
    }

    $topbar_class = 'user_theme_light';
	if ($Settings->get('headerScheme')->val()=='dark') {
		$topbar_class = 'user_theme_dark';
	}

?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Software update</title>
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/styles.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>">
	<!--[if IE]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/ie9.css"><![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
<div class="page-update">
