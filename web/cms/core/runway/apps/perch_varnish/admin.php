<?php
	if (PERCH_RUNWAY) {

		$varnish_config = PerchConfig::get('varnish');

		if ($varnish_config && $varnish_config['enabled']) {

			$this->register_app('perch_varnish', 'Varnish', 1, 'Varnish cache integration', '1.0');

			PerchUtil::debug("Varnish enavled!");

			$API = new PerchAPI(1.0, 'perch_varnish');

			$API->on('page.publish', function($Event) use ($API) {
				$PerchVarnish = new PerchVarnish($API);
				$PerchVarnish->purge($Event->subject);
			});


			spl_autoload_register(function($class_name){
				if (strpos($class_name, 'PerchVarnish')===0) {
					include('PerchVarnish.class.php');
					return true;
				}
				if ($class_name == 'PerchPageRoutes') {
					include(PERCH_CORE.'/runway/PerchPageRoutes.class.php');
					include(PERCH_CORE.'/runway/PerchPageRoute.class.php');
				}
				return false;
			});

		}

	}
