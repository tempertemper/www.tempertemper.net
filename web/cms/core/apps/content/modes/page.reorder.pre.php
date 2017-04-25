<?php
    $Pages      = new PerchContent_Pages;

    $Regions    = new PerchContent_Regions;
    
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    //$Perch->add_javascript(PERCH_LOGINPATH.'/core/assets/js/jquery.ui.nestedSortable.js');
    
    
    
    $Form = $API->get('Form');
    
    if ($Form->posted() && $Form->validate()) {
        

        
        
        
        // JavaScript tree ordering
        if (isset($_POST['orders']) && $_POST['orders']!='') {            
            $pages = explode('&', $_POST['orders']);
            
            $sort_orders = array();
            
            if (PerchUtil::count($pages)) {
                foreach($pages as $str) {
                    if (trim($str)!='') {
                        $parts = explode('=', $str);
                        $pageID = str_replace(array('page[',']'), '', $parts[0]);
                        $parentID = $parts[1];
                        
                        if ($parentID == 'root') $parentID = '0';
                        
                        if (!isset($sort_orders[$parentID])) {
                            $sort_orders[$parentID] = 1;
                        }else{
                            $sort_orders[$parentID]++;
                        }
                        
                        $order = $sort_orders[$parentID];
                        
                        $Page = $Pages->find($pageID);
                        if (is_object($Page)) {
                            $Page->update_tree_position($parentID, $order);
                        }
                    }
                }
            }
            
        }else{
            
            // Basic, non JavaScript ordering within section.
            PerchUtil::debug($_POST);

            $items = $Form->find_items('p-');

            if (PerchUtil::count($items)) {
                foreach($items as $pageID=>$pageOrder) {
                    $Page = $Pages->find($pageID);
                    if (is_object($Page)) {
                        $Page->update_tree_position($Page->pageParentID(), $pageOrder);
                    }
                }
            }
            
        }
        
        $Alert->set('success', PerchLang::get('Page orders successfully updated.'));
        PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/');
    }
