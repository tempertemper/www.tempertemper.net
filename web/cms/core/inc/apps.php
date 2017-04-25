<?php
	$apps_list = array();
	include(PERCH_PATH . '/config/apps.php');

	include(PERCH_PATH.'/core/apps/content/runtime.php');
	include(PERCH_PATH.'/core/apps/assets/runtime.php');
	include(PERCH_PATH.'/core/apps/categories/runtime.php');

	if (PerchUtil::count($apps_list)) {
	    foreach($apps_list as $app_id) {
	        switch($app_id) {
	            case 'content':
	            case 'assets':
	            case 'categories':
	            	// do nothing
	                break;

	            default:
	            	if (!include(PERCH_PATH.'/addons/apps/'.$app_id.'/runtime.php')) {
	            		PerchUtil::debug('Your config/apps.php contains "'.$app_id.'" but that app is not installed.', 'error');
	            	}
	            	break;
	        }
	    }
	}