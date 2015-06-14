<?php

	function perch_get_css($opts=false, $return=false)
	{
		$feathers = PerchSystem::get_registered_feathers();
		$out      = '';

		if (PerchUtil::count($feathers)) {
			$count      = PerchUtil::count($feathers);
			$i          = 0;
			$components = array();

			foreach($feathers as $feather) {
				$classname  = 'PerchFeather_'.$feather;
				$Feather    = new $classname($components);
				$out       .= $Feather->get_css($opts, $i, $count);
				$components = $Feather->get_components();
				$i++;
			}
		}
		if ($return) return $out;
		echo $out;
		PerchUtil::flush_output();
	}

	function perch_get_javascript($opts=false, $return=false)
	{
		$feathers = PerchSystem::get_registered_feathers();
		$out = '';

		if (PerchUtil::count($feathers)) {
			$count      = PerchUtil::count($feathers);
			$i          = 0;
			$components = array();

			foreach($feathers as $feather) {
				$classname  = 'PerchFeather_'.$feather;
				$Feather    = new $classname($components);
				$out       .= $Feather->get_javascript($opts, $i, $count);
				$components = $Feather->get_components();
				$i++;
			}
		}

		// Inject script when authed
		if (isset($_COOKIE['cmsa'])) {
		 	$out .= PerchSystem::get_helper_js();
		}

		if ($return) return $out;
		echo $out;
		PerchUtil::flush_output();
	}

	function perch_path($file, $opts=false, $return=false)
	{
		$out = PERCH_LOGINPATH.'/addons/'.$file;
		if ($return) return $out;
		echo $out;
	}
