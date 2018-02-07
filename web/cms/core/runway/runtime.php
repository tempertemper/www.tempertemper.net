<?php
	include(__DIR__.'/lib/PerchRunway.class.php');
	include(__DIR__.'/lib/PerchConfig.class.php');
	include(__DIR__.'/lib/PerchRouter.class.php');
	include(__DIR__.'/lib/PerchRoutedPage.class.php');

	function perchrunway_extras_autoload($class_name) {

		$extras = [
			'PerchCollectionImporter',
			'PerchCategoryImporter',
		];

		if (in_array($class_name, $extras)) {

			$file = __DIR__.'/lib/' . $class_name . '.class.php';
		
			if (is_readable($file)) {
	            require $file;
	            return true;
	        } else {
	        	die($file);
	        }
		}   
        
        return false;
    }

    spl_autoload_register('perchrunway_extras_autoload');
