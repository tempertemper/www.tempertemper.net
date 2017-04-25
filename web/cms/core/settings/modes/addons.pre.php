<?php
	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');

	if (PerchUtil::get('menu') == 'rebuild') {
		$Menu = new PerchMenu;
		$Menu->rebuild($CurrentUser);
		$Alert->set('success', $Lang->get('Menu successfully rebuilt'));
	}

	$result = PerchUtil::http_get_request('http://', 'activation.grabaperch.com', '/activate/v3/addons/versions/');
	$app_versions = [];

	if ($result) {
		$result = PerchUtil::json_safe_decode($result, 1);
		if (isset($result['result']) && $result['result'] == 'error') {
			$result = false;
		} else {
			if (PerchUtil::count($result)) {
				foreach($result as $app) {
					if ($app['type'] == 'app') {
						$app_versions[$app['name']] = $app['version'];
					}
				}
			}
		}
	}
