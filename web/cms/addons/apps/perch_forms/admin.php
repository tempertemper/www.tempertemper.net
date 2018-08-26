<?php
	if ($CurrentUser->logged_in() && $CurrentUser->has_priv('perch_forms')) {
	    $this->register_app('perch_forms', 'Forms', 1, 'Process data from web forms', '1.12');
	    $this->require_version('perch_forms', '3.1.2');
	}

	spl_autoload_register(function($class_name){
    	if (strpos($class_name, 'PerchForms')===0) {
    		include(PERCH_PATH.'/addons/apps/perch_forms/'.$class_name.'.class.php');
    		return true;
    	}
    	return false;
    });

