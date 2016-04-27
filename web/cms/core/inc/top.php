<?php
    $Settings->get('headerColour')->settingValue();

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

?><!DOCTYPE html>
<html lang="<?php echo $Settings->get('lang')->settingValue(); ?>">
<head>
	<meta charset="utf-8" />
	<title><?php
	    echo PerchUtil::html($Perch->page_title);

	    if (!$Settings->get('hideBranding')->settingValue()) {
	    	if (PERCH_RUNWAY) {
	    		echo PerchUtil::html(' - ' . PerchLang::get('Perch Runway'));
	    	}else{
	    		echo PerchUtil::html(' - ' . PerchLang::get('Perch'));
	    	}

	    }
	?></title>
<?php
	if ($CurrentUser->logged_in()) {
		echo '<meta name="viewport" content="width=device-width" />';
	}else{
		echo '<meta name="viewport" content="width=540" />';
	}

  if ($CurrentUser->logged_in()) { ?>
	<!--[if lt IE 9]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/iebase.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" /><![endif]-->

	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/perch.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/720.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" media="only screen and (min-width: 720px)" />

	<?php if (PERCH_RUNWAY) { ?>
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/runway/assets/css/runway.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" />
	<?php } // runway ?>
	<!--[if lt IE 9]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/720.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" /><![endif]-->
	<!--[if IE 7]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/ie7.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" /><![endif]-->
	<!--[if IE 6]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/ie6.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" /><![endif]-->
<?php }else{ ?>
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/login.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" />
	<?php if (PERCH_RUNWAY) { ?>
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/runway/assets/css/runway_login.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>" type="text/css" />
	<?php } // runway ?>
<?php }

	if ($Settings->get('headerColour')->settingValue()) {
		if ($CurrentUser->logged_in() || trim($Settings->get('headerColour')->settingValue())!='#ffffff') {
	?>  <style type="text/css">.topbar{background-color: <?php echo PerchUtil::html(rtrim($Settings->get('headerColour')->settingValue(), ';')); ?>; }</style>
<?php
		}
		$db_error = false;
	}else {
		// header colour is always set - it's part of the install. If it's not there, the database connection is probably down;
		$db_error = true;
	}

    if ($CurrentUser->logged_in()) {

        $stylesheets = $Perch->get_css();
        foreach($stylesheets as $css) {
            echo "\t".'<link rel="stylesheet" href="'.PerchUtil::html($css).'" type="text/css" />'."\n";
        }

        echo $Perch->get_head_content();

        echo '<script src="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/js/head.min.js?v='.PerchUtil::html($Perch->version).'"></script>';
    }

	if (!$Settings->get('hideBranding')->settingValue()) {
		if (PERCH_RUNWAY) {
			echo '<link rel="shortcut icon" href="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/runway/assets/img/favicon.ico" />';
		}else{
			echo '<link rel="shortcut icon" href="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/img/favicon.ico" />';
		}

	}
?>
</head>
<?php
	flush();

    if ($CurrentUser->logged_in()) {
?>
<body class="<?php
	if (isset($_COOKIE['cmssb'])) {
		if ($_COOKIE['cmssb']=='1') {
			echo 'sidebar-closed ';
		}else{
			echo 'sidebar-open ';
		}
	}else{
		if (PERCH_RUNWAY) {
			echo 'sidebar-closed ';
		}else{
			echo 'sidebar-open ';
		}
	}
?>">
<?php
    }else{
?>
<body class="login">
<?php
    }