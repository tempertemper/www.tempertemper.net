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

    // Don't' allow this to be run twice.
    if ($Settings->get($setting_key)->val()) {

    	if (isset($_GET['force']) && $_GET['force']=='update') {
    		// skip checks
    	}else{
    		PerchUtil::redirect(PERCH_LOGINPATH);	
    	}
   	
    }

    if (isset($_GET['force']) && $_GET['force']=='accept') {
    	$Settings->set($setting_key, 'done');
    	PerchUtil::redirect(PERCH_LOGINPATH.'?logout=1');
    }

    $page_tile = 'Software Update';

    include(PERCH_CORE . '/templates/update/top.php');

?>
	<div class="update-box">
	    <div class="hd">
	        <h1>Software Update</h1>
	    </div>

	    <div class="bd">
	        <ul class="progress-list">
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
				    	echo '<li class="progress-item progress-success">'.PerchUI::icon('core/circle-check').' ';
				    	echo 'Successfully updated to version '.$Perch->version.'.</li>';    
				    	$Settings->set($setting_key, 'done');
				    }

				?>
			</ul>
	    </div>

	    <div class="ft">
	        <?php
					if (!$errors) {
						echo '<a href="'.PERCH_LOGINPATH.'?logout=1" class="button button-simple action-success">Continue</a>';
					}else{
						echo '<div class="notification-block notification-warning">';
						echo '<h2 class="notification-heading">'.PerchUI::icon('core/alert').' Please check the messages</h2>';
						echo '<p><a href="https://grabaperch.com/support" class="notification-link">Contact us</a> if you are unsure how to resolve these problems, or <a href="'.PERCH_LOGINPATH.'/core/update/?force=accept" class="notification-link">accept these errors and continue</a>.</p>';
						echo '</div>';
					}
				?>
	    </div>
	</div>
<?php

include(PERCH_CORE . '/templates/update/btm.php');