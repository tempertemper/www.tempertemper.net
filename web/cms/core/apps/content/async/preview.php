<?php
	include(realpath(__DIR__ . '/../../..').'/inc/pre_config.php');
    include(realpath(__DIR__ . '/../../../..').'/config/config.php');
    include(PERCH_CORE . '/inc/loader.php');
    include(PERCH_CORE . '/runtime/core.php');
    include(PERCH_CORE . '/inc/apps.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth_light.php');

    $tag_str = base64_decode(PerchRequest::post('tag64'));
    $Tag = new PerchXMLTag($tag_str);
    
    $FieldType = PerchFieldTypes::get($Tag->type(), false, $Tag);
    $raw =  $FieldType->get_raw([
    		$Tag->id() => PerchRequest::post('text')
    	]);

    echo $FieldType->get_processed($raw);