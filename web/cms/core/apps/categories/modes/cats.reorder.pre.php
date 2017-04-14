<?php

	$API  = new PerchAPI(1.0, 'categories');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Sets = new PerchCategories_Sets;
	$Categories = new PerchCategories_Categories;

	if (isset($_GET['id']) && $_GET['id']!='') {
		$setID = (int) $_GET['id'];
		$Set = $Sets->find($setID);
	}else{
		$setID = false;
		$Set   = false;
	}
	
	$Form = new PerchForm('order');
	
	if ($Form->posted() && $Form->validate()) { 
	    	    
	    // JavaScript tree ordering
	    if (isset($_POST['orders']) && $_POST['orders']!='') {            
	        $categories = explode('&', $_POST['orders']);
	        
	        $sort_orders = array();
	        
	        if (PerchUtil::count($categories)) {
	            foreach($categories as $str) {
	                if (trim($str)!='') {
	                    $parts = explode('=', $str);
	                    $catID = str_replace(array('category[',']'), '', $parts[0]);
	                    $parentID = $parts[1];
	                    
	                    if ($parentID == 'root') $parentID = '0';
	                    
	                    if (!isset($sort_orders[$parentID])) {
	                        $sort_orders[$parentID] = 1;
	                    }else{
	                        $sort_orders[$parentID]++;
	                    }
	                    
	                    

	                    $order = $sort_orders[$parentID];
	                    
	                    $Category = $Categories->find($catID);
	                    if (is_object($Category)) {
	                        $Category->update_tree_position($parentID, $order);
	                    }
	                }
	            }
	        }
	        
	    }else{
	        
	        // Basic, non JavaScript ordering within section.
	        PerchUtil::debug($_POST);

	        $items = $Form->find_items('c-');

	        if (PerchUtil::count($items)) {
	            foreach($items as $catID=>$catOrder) {
	                $Category = $Categories->find($catID);
	                if (is_object($Category)) {
	                    $Category->update_tree_position($Category->catParentID(), $catOrder);
	                }
	            }
	        }
	        
	    }
	    
	    $Alert->set('success', PerchLang::get('Category orders successfully updated.'));
		PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/categories/sets/?id='.$setID);
	}
