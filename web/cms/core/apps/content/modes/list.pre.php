<?php
	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');

    $Pages      = new PerchContent_Pages;
    $Regions    = new PerchContent_Regions;

	$expand_list = array(0);

    // Find new pages and initialise
    $Pages->order_new_pages();
    
    // Collapse list?
    $do_list_collapse = $Settings->get('content_collapseList')->val();
    //$Perch->add_javascript_block("Perch.Apps.Content.settings = { 'collapseList':".(PerchUtil::bool_val($do_list_collapse) ? 'true':'false')." };");


	// default state
	$filter 	= 'all';
	$do_regions = false;
	$show_shared = true;
	
	
	if (isset($_GET['filter']) && $_GET['filter']=='new') {
		$filter = 'new';
		$do_regions = true;
		$do_list_collapse = false;
		$show_shared = false;
	}
	
	if (isset($_GET['template']) && $_GET['template']!='') {
		$filter = 'template';
		$do_regions = true;
		$do_list_collapse = false;
		$show_shared = true;
		$template_to_filter = urldecode($_GET['template']);
	}

	if (isset($_GET['show-filter']) && $_GET['show-filter']!='') { 
		$filter = $_GET['show-filter'];
	}
	


    // Get pages
    if ($do_list_collapse) {
        $expand_list = array(0);
        
        // get the existing expand list
        if (PerchSession::is_set('content_expand_list')) {
            $expand_list = PerchSession::get('content_expand_list');
        }
        
        // find any new actions
        if (isset($_GET['ex']) && $_GET['ex']!='') {
            // expand
            $actionID = (int) $_GET['ex'];
            
            $current_index = array_search($actionID, $expand_list);
            if ($current_index===false) {
                $expand_list[] = $actionID;
            }
            
        }
        
        if (isset($_GET['cl']) && $_GET['cl']!='') {
            // close
            $actionID = (int) $_GET['cl'];
            
            $current_index = array_search($actionID, $expand_list);
            if ($current_index!==false) {
                unset($expand_list[$current_index]);
                
                // find any expanded children
                $child_ids = $Pages->find_child_page_ids($actionID);
                $expand_list = array_diff($expand_list, $child_ids);

            }
        }
        
        PerchSession::set('content_expand_list', $expand_list);
        
        
        $pages = $Pages->get_page_tree_collapsed($expand_list);

        if (PERCH_RUNWAY && PerchUtil::count($pages)==0) {
			$Pages->create_defaults($CurrentUser);
			$pages = $Pages->get_page_tree_collapsed(array(0));
		}
		
    }else{
		
		switch($filter) {
			
			case 'new':
				$pages = $Pages->get_page_tree_filtered('new');
				break;
				
				
			case 'template':
				$pages = $Pages->get_page_tree_filtered('template', $template_to_filter);
				break;
				
			default:
				$pages = $Pages->get_page_tree();

				if (PERCH_RUNWAY && PerchUtil::count($pages)==0) {
					$Pages->create_defaults($CurrentUser);
					$pages = $Pages->get_page_tree_collapsed(array(0));
				}
				
				break;
			
		}
   	}
    



    // Preload regions
	if ($filter=='all') $Regions->preload_regions();

    // get shared regions
	if ($show_shared) {
		
		switch($filter) {
			
			case 'template':
				$shared_regions = $Regions->get_shared(null, $template_to_filter);

				break;
				
			default:
				$shared_regions = $Regions->get_shared(null);
				break;
			
		}
		
	    
	    if (PerchUtil::count($shared_regions)) {
	        $SharedPage = $Pages->get_mock_shared_page();
	        if (!PerchUtil::count($pages)) $pages = array();
	        
	        array_unshift($pages, $SharedPage);
	    }
    }

