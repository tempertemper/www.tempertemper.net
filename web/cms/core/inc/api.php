<?php
	include('pre_config.php');

    include(__DIR__ . '/../../config/config.php');
    
    if (!defined('PERCH_PRODUCTION_MODE')) define('PERCH_PRODUCTION_MODE', PERCH_PRODUCTION);
    
    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
