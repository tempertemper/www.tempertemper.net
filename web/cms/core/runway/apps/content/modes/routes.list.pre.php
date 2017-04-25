<?php
  
    $Routes = new PerchPageRoutes;

    $API  = new PerchAPI(1.0, 'content');
    $HTML = $API->get('HTML');
	$Lang   = $API->get('Lang');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(1000);

    $routes = $Routes->get_routes_for_admin_edit($Paging);