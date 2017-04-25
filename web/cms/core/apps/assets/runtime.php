<?php

	spl_autoload_register(function($class_name){
        if (strpos($class_name, 'PerchAssets')===0) {
            include(PERCH_CORE.'/apps/assets/'.$class_name.'.class.php');
            return true;
        }
        return false;
    });

	PerchSystem::register_shortcode_provider('PerchAssets_ShortcodeProvider');