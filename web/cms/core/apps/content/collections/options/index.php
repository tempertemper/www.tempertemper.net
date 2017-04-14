<?php
    include(realpath(__DIR__ . '/../../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    if (!PERCH_RUNWAY) exit;
    
    $Perch->page_title = PerchLang::get('Collection Options');

    $app_path = PERCH_CORE.'/apps/content';
    
    include($app_path.'/PerchContent_Item.class.php');
    include($app_path.'/PerchContent_Items.class.php');
    include($app_path.'/PerchContent_Page.class.php');
    include($app_path.'/PerchContent_Pages.class.php');
    include($app_path.'/PerchContent_Region.class.php');
    include($app_path.'/PerchContent_Regions.class.php');
    include(PERCH_CORE.'/runway/apps/content/PerchContent_Collections.class.php');
    include(PERCH_CORE.'/runway/apps/content/PerchContent_Collection.class.php');
    include(PERCH_CORE.'/runway/apps/content/PerchContent_CollectionItems.class.php');
    include(PERCH_CORE.'/runway/apps/content/PerchContent_CollectionItem.class.php');
    
        
    include(PERCH_CORE.'/runway/apps/content/modes/collection.options.pre.php');
    include($app_path.'/modes/_subnav.php');
    include(PERCH_CORE . '/inc/top.php');

    include(PERCH_CORE.'/runway/apps/content/modes/collection.options.post.php');

    include(PERCH_CORE . '/inc/btm.php');
?>