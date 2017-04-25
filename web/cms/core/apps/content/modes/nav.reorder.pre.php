<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    
    $NavGroups  = new PerchContent_NavGroups;
    $Pages      = new PerchContent_Pages;

        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $groupID = (int) $_GET['id'];    
        $NavGroup = $NavGroups->find($groupID);
    }else{
        $groupID = false;
        $NavGroup = false;
    }
    
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
                        
                        if ($parentID == 'root' || $parentID == 'null') $parentID = '0';
                        
                        if (!isset($sort_orders[$parentID])) {
                            $sort_orders[$parentID] = 1;
                        }else{
                            $sort_orders[$parentID]++;
                        }
                        
                        $order = $sort_orders[$parentID];
                                   
                        $NavGroup->update_tree_position($pageID, $parentID, $order);
                  
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
                    $NavGroup->update_tree_position($pageID, $NavGroup->page_parent($pageID), $order);
                }
            }
            
        }
        
        $Alert->set('success', PerchLang::get('Page orders successfully updated.'));
        PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/navigation/pages/?id='.$groupID);
    }

