<?php
    include(__DIR__ . '/../../../inc/pre_config.php');
    include(__DIR__ . '/../../../../config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    $Perch->page_title = PerchLang::get('Reorder Categories');

    include(__DIR__.'/../PerchCategories_Categories.class.php');
    include(__DIR__.'/../PerchCategories_Category.class.php');
    include(__DIR__.'/../PerchCategories_Sets.class.php');
    include(__DIR__.'/../PerchCategories_Set.class.php');
            
    include(__DIR__.'/../modes/cats.reorder.pre.php');
    include(__DIR__.'/../modes/_subnav.php');
   
    include(PERCH_CORE.'/inc/top.php');

    include(__DIR__.'/../modes/cats.reorder.post.php');

    include(PERCH_CORE.'/inc/btm.php');
