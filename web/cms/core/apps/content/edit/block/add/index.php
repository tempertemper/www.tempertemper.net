<?php
    include(realpath(__DIR__ . '/../../../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    
    $Perch->page_title = PerchLang::get('Add a block');

    $app_path = PERCH_CORE.'/apps/content';
    
    include($app_path.'/PerchContent_Item.class.php');
    include($app_path.'/PerchContent_Items.class.php');
    include($app_path.'/PerchContent_Page.class.php');
    include($app_path.'/PerchContent_Pages.class.php');
    include($app_path.'/PerchContent_Region.class.php');
    include($app_path.'/PerchContent_Regions.class.php');
    include($app_path.'/PerchContent_Util.class.php');
    
        
    include($app_path.'/modes/block.async.add.pre.php');
    
    //include(PERCH_CORE . '/inc/top.php');

    include($app_path.'/modes/block.async.add.post.php');

    //include(PERCH_CORE . '/inc/btm.php');
