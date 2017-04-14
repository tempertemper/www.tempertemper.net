<?php
    $API    = new PerchAPI(1.0, 'content');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $Paging = $API->get('Paging');
    $Paging->set_per_page(100);
    

    $Pages   = new PerchContent_Pages;
    $Regions = new PerchContent_Regions;
    $Page  = false;
    $collections = false;
    
    // Find the page
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];

		if ($id==-1) {
			$Page = $Pages->get_mock_shared_page();
		}else{
			$Page = $Pages->find($id);
		}

        
    }
    
    // Check we have a page
    if (!$Page || !is_object($Page)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }
    

    if (PERCH_RUNWAY) {
        $Collections = new PerchContent_Collections;

        if ($Page->pageCollections()) {
            $collections = $Collections->get_by_id_string($Page->pageCollections());
        }

    }

	if ($Page->pagePath()=='*') {
        $regions = $Regions->get_shared($Paging);
    }else{
        $regions = $Regions->get_for_page($Page->id(), $include_shared=false, $new_only=false, $template=false, $Paging);
    }


    if (PerchUtil::get('f')=='pl' && PerchUtil::count($regions) == 1 && $collections == false) {
        // link is from page list (f=pl)
        // and only one region, so jump directly to it.
        $item = $regions[0];
        if ($item->role_may_edit($CurrentUser)) {
            $page = '../edit/?id='.$item->id();
            PerchUtil::redirect($page);
        }
    }