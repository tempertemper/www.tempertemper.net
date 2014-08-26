<?php
	include(__DIR__ . '/../inc/pre_config.php');
	include(__DIR__ . '/../../config/config.php');
	include(PERCH_CORE . '/inc/loader.php');
	$Perch  = PerchAdmin::fetch();
	include(PERCH_CORE . '/inc/auth_light.php');
	header('Content-Type: application/javascript');
	echo "Perch.Privs.init(\n\t";
		echo PerchUtil::json_safe_encode($CurrentUser->get_privs());
	echo ');';
