<?php
	$API  = new PerchAPI(1.0, 'content');
	$HTML = $API->get('HTML');

	$Routes = new PerchPageRoutes;

	$Form = new PerchForm('order');
	
	if ($Form->posted() && $Form->validate()) { 
	    	    
	    $items = $Form->find_items('item_');
	    if (PerchUtil::count($items)) {
	        foreach($items as $itemID=>$itemOrder) {
	            $Route = $Routes->find($itemID);
	            if (is_object($Route)) {
	                $Route->update(['routeOrder' => $itemOrder ]);
	            }
	        }

	        $Alert->set('success', PerchLang::get('Route orders successfully updated.'));
	    }
	    
	    
	
	}

	$routes = $Routes->get_routes_for_admin_edit();
