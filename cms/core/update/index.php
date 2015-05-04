<?php
	$auth_page = true;
	$done      = false;
	
	$errors    = false;



    include('../inc/pre_config.php');
    include('../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');

    $Perch  = PerchAdmin::fetch();

    $setting_key = 'update_'.$Perch->version;
  	if (PERCH_RUNWAY) $setting_key = 'update_runway_'.$Perch->version;

    include(PERCH_CORE . '/inc/auth.php');

    // Done allow this to be run twice.
    if ($Settings->get($setting_key)->val()) {

    	if (isset($_GET['force']) && $_GET['force']=='update') {
    		// skip checks
    	}else{
    		PerchUtil::redirect(PERCH_LOGINPATH);	
    	}
   	
    }

    if (isset($_GET['force']) && $_GET['force']=='accept') {
    	$Settings->set($setting_key, 'done');
    	PerchUtil::redirect(PERCH_LOGINPATH);
    }

    include(PERCH_CORE.'/apps/content/PerchContent_Regions.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Region.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Items.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Item.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Pages.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Page.class.php');


?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Software Update</title>
<?php
    if ($CurrentUser->logged_in()) {
?>
	<!--[if lt IE 9]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/css/iebase.css?v=<?php echo PerchUtil::html($Perch->version); ?>" type="text/css" /><![endif]-->
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/css/perch.css?v=<?php echo PerchUtil::html($Perch->version); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/css/720.css?v=<?php echo PerchUtil::html($Perch->version); ?>" type="text/css" media="only screen and (min-width: 720px)" />
	<!--[if lt IE 9]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/css/720.css?v=<?php echo PerchUtil::html($Perch->version); ?>" type="text/css" /><![endif]-->
	<!--[if IE 7]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/css/ie7.css?v=<?php echo PerchUtil::html($Perch->version); ?>" type="text/css" /><![endif]-->
	<!--[if IE 6]><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/css/ie6.css?v=<?php echo PerchUtil::html($Perch->version); ?>" type="text/css" /><![endif]-->	
<?php 
		if (PERCH_DEBUG) {?><link rel="stylesheet" href="<?php echo PerchUtil::html(PERCH_LOGINPATH); ?>/core/assets/css/debug.css" type="text/css" /><?php } 	
	} 
?>    
</head>
<body class="sidebar-closed">
	<div class="main">
		<div class="body">
			<div class="inner">
				<h1>Software Update</h1>

				<ul class="updates">
				<?php
					$files = PerchUtil::get_dir_contents('scripts', false);

					$DB = PerchDB::fetch();

					if (PerchUtil::count($files)) {
						foreach($files as $file) {
							if (PerchUtil::file_extension($file) == 'php') {
								include PerchUtil::file_path(PERCH_CORE.'/update/scripts/'.$file);
							}
						}
					}

				   	if (!$errors) {
				    	echo '<li class="icon success">Successfully updated to version '.$Perch->version.'.</li>';    
				    	$Settings->set($setting_key, 'done');
				    }

				?>
				</ul>
				<?php
					if (!$errors) {
						echo '<a href="'.PERCH_LOGINPATH.'" class="button">Continue</a>';
					}else{
						echo '<p><a href="http://support.grabaperch.com/">Contact us</a> if you are unsure how to resolve these problems, or <a href="'.PERCH_LOGINPATH.'/core/update/?force=accept">accept these errors and continue</a>.</p>';
					}
				?>

			</div>
		</div>

	</div>
<?php
	if (PERCH_DEBUG) {
    	PerchUtil::debug('Queries: '. PerchDB_MySQL::$queries);
    	PerchUtil::output_debug(); 
    }
?>
</body>
</html>