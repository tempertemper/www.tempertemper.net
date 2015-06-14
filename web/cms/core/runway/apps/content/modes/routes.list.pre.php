<?php
  
    $Routes = new PerchPageRoutes;

    $API  = new PerchAPI(1.0, 'content');
    $HTML = $API->get('HTML');

    $routes = $Routes->get_routes_for_admin_edit();