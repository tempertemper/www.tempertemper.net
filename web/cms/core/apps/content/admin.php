<?php
    $this->register_app('content', 'Pages', 1, 'Default app for managing content', $this->version);
    $this->add_setting('content_collapseList', 'Collapse content list', 'checkbox', false);
    $this->add_setting('content_singlePageEdit', 'Default to single-page edit mode', 'checkbox', false);
    $this->add_setting('content_hideNonEditableRegions', 'Hide regions you can\'t edit', 'checkbox', false);
    $this->add_setting('content_frontend_edit', 'Enable Ctrl-E to edit', 'checkbox', false);
    $this->add_setting('content_skip_region_list', 'Skip region list for just one region', 'checkbox', false);

    PerchSystem::register_admin_search_handler('PerchContent_SearchHandler');
    if (PERCH_RUNWAY) {
        PerchSystem::register_admin_search_handler('PerchContent_RunwaySearch');
    }


    spl_autoload_register(function($class_name){
        if (strpos($class_name, 'PerchContent_Runway')===0) {
            include(PERCH_CORE.'/runway/apps/content/'.$class_name.'.class.php');
            return true;
        }
    	if (strpos($class_name, 'PerchContent')===0) {
    		include(PERCH_CORE.'/apps/content/'.$class_name.'.class.php');
    		return true;
    	}
    	return false;
    });