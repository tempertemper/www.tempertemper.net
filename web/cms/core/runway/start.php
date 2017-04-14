<?php
	$runway_request_time = microtime(true);
	if (PHP_VERSION_ID<50400) die('Perch Runway requires PHP 5.4 or greater.');

	define('PERCH_RUNWAY_ROUTED', true);

	include(__DIR__.'/../runtime/runtime.php');
	if (!PERCH_RUNWAY) die('Perch Runway requires a Runway license key.');

	# Routing
	$Router 	= new PerchRouter;
	$RoutedPage = $Router->get_route($_SERVER['REQUEST_URI']);

	function perch_runway_dispatch_page($RoutedPage, $redispatch=false) {
		if ($RoutedPage) {
			PerchSystem::set_page($RoutedPage);
			$Runway = PerchRunway::fetch();
			$Runway->set_page($RoutedPage);
			if (PerchUtil::count($RoutedPage->args)) foreach($RoutedPage->args as $key=>$val) PerchSystem::set_var('url_'.$key, $val);

			if (PERCH_DEBUG) {
				PerchUtil::debug('Using master page: '.str_replace(PERCH_PATH, '', $RoutedPage->template), 'routing');
				
				if (PerchUtil::count($RoutedPage->args)) {
					PerchUtil::debug('Page arguments: <pre>'.trim(print_r($RoutedPage->args, true)).'</pre>', 'routing');
				}
			}

			if ($RoutedPage->api_request) {
				if (!file_exists($RoutedPage->template)) {
					$RoutedPage = new PerchRoutedPage(false, false, false, false, 'errors/404.php', 404);
				}
				$API = new PerchAPI(1.0, 'api_request');
			} else {
				$Perch = Perch::fetch();
				$Perch->event('page.loaded');		
			}

			$RoutedPage->output_headers();
			
			PerchUtil::invalidate_opcache($RoutedPage->template);
			
			include($RoutedPage->template);	
		}
	}

	perch_runway_dispatch_page($RoutedPage);

	if (PERCH_DEBUG && !$RoutedPage->api_request) {
		PerchUtil::debug('Request time: '.round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4));
		PerchUtil::debug('Process time: '.round(microtime(true) - $runway_request_time, 4));
		PerchUtil::debug('Memory: '. round(memory_get_peak_usage()/1024/1024, 4));
		PerchUtil::output_debug();
	}
