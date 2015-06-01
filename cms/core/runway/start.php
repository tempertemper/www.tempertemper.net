<?php
	if (PHP_VERSION_ID<50400) die('Perch Runway requires PHP 5.4 or greater.');

	include(__DIR__.'/../runtime/runtime.php');

	if (!PERCH_RUNWAY) die('Perch Runway requires a Runway license key.');

	# Routing
	$Router 	= new PerchRouter;
	$RoutedPage = $Router->get_route($_SERVER['REQUEST_URI']);
	
	if ($RoutedPage) {
		PerchSystem::set_page($RoutedPage);
		$Runway = PerchRunway::fetch();
		$Runway->set_page($RoutedPage);
		if (PerchUtil::count($RoutedPage->args)) foreach($RoutedPage->args as $key=>$val) PerchSystem::set_var('url_'.$key, $val);

		if (PERCH_DEBUG) {
			PerchUtil::debug('Using master page: '.str_replace(PERCH_PATH, '', $RoutedPage->template), 'template');
			PerchUtil::debug('Page arguments: <pre>'.print_r($RoutedPage->args, true).'</pre>', 'template');		
		}

		perch_find_posted_forms();
		perch_runway_content_check_preview();

		$RoutedPage->output_headers();
		include($RoutedPage->template);	
	}

	if (PERCH_DEBUG) {
		PerchUtil::debug('Time: '.round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4));
		PerchUtil::debug('Memory: '. round(memory_get_peak_usage()/1024/1024, 4));
		PerchUtil::output_debug();
	}
