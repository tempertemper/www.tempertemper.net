<?php
	if ($CurrentUser->has_priv('categories.manage')) {
		$this->register_app('categories', 'Categories', 1.2, 'Category management', $this->version);	
	}

	spl_autoload_register(function($class_name){
    	if (strpos($class_name, 'PerchCategories')===0) {
    		include(PERCH_CORE.'/apps/categories/'.$class_name.'.class.php');
    		return true;
    	}
    	return false;
    });