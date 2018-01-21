<?php
    include(realpath(__DIR__ . '/../../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth_light.php');

    include(__DIR__.'/../../PerchAssets_Tags.class.php');
    include(__DIR__.'/../../PerchAssets_Tag.class.php');

    $Tags = new PerchAssets_Tags();

    $results = $Tags->async_search($_GET['term']);

    echo PerchUtil::json_safe_encode($results);