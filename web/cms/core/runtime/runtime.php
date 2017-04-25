<?php
    if (defined('PERCH_ERROR_MODE')) {
      if (strpos($_SERVER['SCRIPT_NAME'], 'start.php')) {
        die('You have included the Perch runtime in your page template. Please remove it - Runway will include it for you.');
      }else{
        die('You have included the Perch runtime in your page more than once. Please only include it once.');
      }
    }

    define('PERCH_ERROR_MODE', 'SILENT');
	  include(__DIR__.'/../inc/pre_config.php');
    include(__DIR__.'/../../config/config.php');
    if (!defined('PERCH_PRODUCTION_MODE')) define('PERCH_PRODUCTION_MODE', PERCH_PRODUCTION);
    include(PERCH_CORE . '/runtime/loader.php');
    include(PERCH_CORE . '/runtime/core.php');
    include(PERCH_CORE . '/inc/apps.php');
    include(PERCH_PATH . '/core/inc/forms.php');
   	if (PERCH_FEATHERS && file_exists(PERCH_PATH . '/config/feathers.php')){
      include(PERCH_PATH . '/config/feathers.php');
   	}
    include(PERCH_PATH . '/core/inc/feathers.php');