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

    $Perch->add_javascript(PERCH_LOGINPATH.'/core/inc/js_lang.php');

?><!doctype html>
<html lang="<?php echo PerchUtil::html($lang); ?>">
<head>
	<meta charset="utf-8">
	<title><?php echo PerchUtil::html($page_title); ?></title>
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/styles.css?v=<?php echo PerchUtil::html($Perch->version, true); ?>">
	<!--[if IE]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH, true); ?>/core/assets/css/ie9.css"><![endif]-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<style type="text/css">.topbar.custom { background-color: <?php echo PerchUtil::html(PerchUtil::never_white(rtrim($headerColour)), ';'); ?>; }
		.dialog-overlay { background-color: <?php echo PerchUtil::html(PerchUtil::never_white(rtrim($headerColour), 'd1d1d1'), ';'); ?>; }
	</style>
	<?php  

    $stylesheets = $Perch->get_css();
    foreach($stylesheets as $css) {
        echo '<link rel="stylesheet" href="'.PerchUtil::html($css).'">'."\n";
    }

    echo $Perch->get_head_content();
?>
<script>
	if (typeof(Perch) == 'undefined') {
		Perch           = {};
		Perch.UI        = {};
		Perch.Apps      = {};
	}
	Perch.token   = '<?php $CSRFForm = new PerchForm('csrf'); echo $CSRFForm->get_token(); ?>';
	Perch.path    = '<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>';
	Perch.version = '<?php echo $Perch->version; ?>';
	Perch.theme   = '<?php echo PerchUtil::html(rtrim($headerColour)); ?>';
	Perch.Runway  = <?php echo (PERCH_RUNWAY ? 'true' : 'false'); ?>;
	Perch.UI.enableKeyboardShortcuts = <?php echo ($Settings->get('keyboardShortcuts')->val() ? 'true' : 'false'); ?>;
</script>
<?php
    echo '<script src="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/js/static/vendor.'.PERCH_ASSET_VERSION.'.js"></script>'."\n";
    echo '<script src="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/js/static/app.'.PERCH_ASSET_VERSION.'.js"></script>'."\n";

	if (!$hideBranding) {
		if (PERCH_RUNWAY) {
			echo '	<link rel="shortcut icon" href="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/runway/assets/img/favicon.ico">';
		}else{
			echo '	<link rel="shortcut icon" href="'.PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/img/favicon.ico">';
		}
	}
?>
</head><?php
	flush();
?>
<body>
<div class="page" id="page">
<?php include('topbar.php'); ?>
<div class="panels">
<?php include('sidebar.php'); ?>
<!-- MAIN PANEL -->
<main class="main-panel" id="main" aria-label="Content">