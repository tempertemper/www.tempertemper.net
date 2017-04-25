<div id="dashboard" class="dashboard">
<?php

    $apps = $Perch->get_apps();

    $order = $Settings->get('dashboard_order')->val();
    $app_ids = array();

	foreach($apps as $app) {
      	if ($app['dashboard']) {
      		$app_ids[] = $app['id'];
      	} 
    }

    if ($order) {
    	$order = explode(',', $order);
    	$order = array_merge($order, array_diff($app_ids, $order));
    }else{
    	$order = $app_ids;
    }

    if (PerchUtil::count($order)) {
    	foreach($order as $appID) {
    		foreach($apps as $app) {
		        if ($app['id']==$appID && $app['dashboard']) {
		            
		            $func = include($app['dashboard']);

                    if (is_callable($func)) {
                        $str = $func($CurrentUser);

                        $result = preg_match('#<div ([^>]*)>#', $str, $matches);

                        if ($result) {
                            if (PerchUtil::count($matches)) {
                                $replacement = '<div data-app="'.$app['id'].'" '.$matches[1].'>';
                                $str = str_replace($matches[0], $replacement, $str);
                            }
                        }

                        echo $str;
                    }

		            
                    
		        }
		    }
    	}
    }

?>
</div>