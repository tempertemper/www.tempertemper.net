<?php
    include(realpath(__DIR__ . '/../../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    
    $Perch->page_title = PerchLang::get('Master Pages');

    $app_path = PERCH_CORE.'/apps/content';
    
    include($app_path.'/PerchContent_Item.class.php');
    include($app_path.'/PerchContent_Items.class.php');
    include($app_path.'/PerchContent_Page.class.php');
    include($app_path.'/PerchContent_Pages.class.php');
    include($app_path.'/PerchContent_Region.class.php');
    include($app_path.'/PerchContent_Regions.class.php');
    include($app_path.'/PerchContent_PageTemplates.class.php');
    include($app_path.'/PerchContent_PageTemplate.class.php');
    
        
    include($app_path.'/modes/template.list.pre.php');
    include($app_path.'/modes/_subnav.php');
    include(PERCH_CORE . '/inc/top.php');

    include($app_path.'/modes/template.list.post.php');

    include(PERCH_CORE . '/inc/btm.php');
