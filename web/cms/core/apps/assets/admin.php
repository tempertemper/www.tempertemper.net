<?php
	if ($CurrentUser->has_priv('assets.manage')) {
    	$this->register_app('assets', 'Assets', 1.1, 'Asset management', $this->version);

        if (PERCH_RUNWAY) {
            PerchSystem::register_admin_search_handler('PerchAssets_SearchHandler');
        }
    }

    PerchSystem::register_shortcode_provider('PerchAssets_ShortcodeProvider');

    spl_autoload_register(function($class_name){
    	if (strpos($class_name, 'PerchAssets')===0) {
    		include(PERCH_CORE.'/apps/assets/'.$class_name.'.class.php');
    		return true;
    	}
    	return false;
    });