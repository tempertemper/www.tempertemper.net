<?php
    define('PERCH_ERROR_MODE', 'SILENT');
	  include(__DIR__.'/../inc/pre_config.php');
    include(__DIR__.'/../../config/config.php');
    if (!defined('PERCH_PRODUCTION_MODE')) define('PERCH_PRODUCTION_MODE', PERCH_PRODUCTION);
    include(PERCH_CORE . '/runtime/loader.php');
    $apps_list = array();
    include(PERCH_PATH . '/config/apps.php');
    if (PerchUtil::count($apps_list)) {
        foreach($apps_list as $app_id) {
            switch($app_id) {
                case 'content':    include(PERCH_PATH.'/core/apps/content/runtime.php'); break;
                case 'assets':     include(PERCH_PATH.'/core/apps/assets/runtime.php'); break;
                case 'categories': include(PERCH_PATH.'/core/apps/categories/runtime.php'); break;
                default:           include(PERCH_PATH.'/addons/apps/'.$app_id.'/runtime.php');
            }
        }
    }
    include(PERCH_PATH . '/core/inc/forms.php');
   	
   	if (file_exists(PERCH_PATH . '/config/feathers.php')){
      include(PERCH_PATH . '/config/feathers.php');
      include(PERCH_PATH . '/core/inc/feathers.php');
   	}