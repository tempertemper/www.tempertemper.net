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
<html lang="<?php echo PerchUtil::html($lang); ?>">
<head>
	<meta charset="utf-8">
	<title><?php echo PerchUtil::html($page_title);	?></title>
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/styles.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>">
	<!--[if IE]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/ie9.css"><![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php
	if ($headerColour!='#ffffff') { ?>  
	<style type="text/css">.login-box .hd.topbar{background-color: <?php echo PerchUtil::html(rtrim($headerColour, ';')); ?>; }</style><?php 
	} 
 		
	if (!$hideBranding) {
		if (PERCH_RUNWAY) {
			echo '<link rel="shortcut icon" href="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/runway/assets/img/favicon.ico" />';
		}else{
			echo '<link rel="shortcut icon" href="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/img/favicon.ico" />';
		}
	}
?>
	<link rel="prefetch" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/svg/core.svg">
	<link rel="prefetch" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/svg/assets.svg">
</head>
<body>

<div class="page-login">
	<div class="fix-flex-height">
