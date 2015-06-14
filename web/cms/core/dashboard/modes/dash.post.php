<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
	<p><?php echo PerchLang::get('Welcome. The dashboard gives you an overview of the content on your website.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<div id="dashboard" class="dash">
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
		            ob_start();
		            include($app['dashboard']);
		            $str = ob_get_clean();

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
  
    
    


?>
</div>
<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>