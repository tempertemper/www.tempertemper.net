<?php

	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');




	$results = false;
    $PerchAdminSearch = new PerchAdminSearch;
    
    if (PerchUtil::get('q')) {	
		$results = $PerchAdminSearch->search(PerchUtil::get('q'), [
				'count' => 100,

			]);	    	
		if (!$results) {
			$Alert->set('info', 'Sorry, there are no results for ‘'.PerchUtil::html(PerchUtil::get('q')).'’');
		}
	}


