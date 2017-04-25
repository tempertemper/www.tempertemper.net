<?php
	include(__DIR__.'/../lib/PerchRunway.class.php');
	include(__DIR__.'/../lib/PerchConfig.class.php');
	include(__DIR__.'/../lib/PerchRouter.class.php');
	include(__DIR__.'/../lib/PerchRoutedPage.class.php');
	include(__DIR__.'/../lib/PerchPageRoutes.class.php');
	include(__DIR__.'/../lib/PerchPageRoute.class.php');
	include(__DIR__.'/../lib/PerchAdminSearch.class.php');
	include(__DIR__.'/../lib/vendor/autoload.php');

	function runway_autoload($class_name) {
	    if (strpos($class_name, 'PerchContent')!==false) {
	        $file = PERCH_CORE . '/runway/apps/content/' . $class_name . '.class.php';
	    }else{
	        $file = PERCH_CORE . '/runway/lib/' . $class_name . '.class.php';
	    }   
	    
	    if (is_readable($file)) {
	        require $file;
	        return true;
	    }
	    return false;
    }

    spl_autoload_register('runway_autoload');
