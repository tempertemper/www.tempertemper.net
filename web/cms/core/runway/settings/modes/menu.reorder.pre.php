<?php
	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(24);

	$MenuItems = new PerchMenuItems;


	$Form = new PerchForm('order');

	if ($Form->posted() && $Form->validate()) { 
	    	    
	    // JavaScript tree ordering
	    if (isset($_POST['orders']) && $_POST['orders']!='') {            
	        $menuitems = explode('&', $_POST['orders']);
	        
	        $sort_orders = array();
	        
	        if (PerchUtil::count($menuitems)) {
	            foreach($menuitems as $str) {
	                if (trim($str)!='') {
	                    $parts = explode('=', $str);
	                    $itemID = str_replace(array('category[',']'), '', $parts[0]);
	                    $parentID = $parts[1];
	                    
	                    if ($parentID == 'root') $parentID = '0';
	                    
	                    if (!isset($sort_orders[$parentID])) {
	                        $sort_orders[$parentID] = 1;
	                    }else{
	                        $sort_orders[$parentID]++;
	                    }
	                    
	                    

	                    $order = $sort_orders[$parentID];
	                    
	                    $MenuItem = $MenuItems->find($itemID);
	                    if (is_object($MenuItem)) {
	                        $MenuItem->update_tree_position($parentID, $order);
	                    }
	                }
	            }
	        }
	        
	    }else{
	        
	        // Basic, non JavaScript ordering within section.
	        PerchUtil::debug($_POST);

	        $items = $Form->find_items('c-');

	        if (PerchUtil::count($items)) {
	            foreach($items as $itemID=>$catOrder) {
	                $MenuItem = $MenuItems->find($itemID);
	                if (is_object($MenuItem)) {
	                    $MenuItem->update_tree_position($MenuItem->catParentID(), $catOrder);
	                }
	            }
	        }
	        
	    }
	    

		PerchUtil::redirect(PERCH_LOGINPATH.'/core/settings/menu/');
	}