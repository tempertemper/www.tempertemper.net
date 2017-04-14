<?php
    
    $Collections = new PerchContent_Collections;
    $Regions     = new PerchContent_Regions;
    $Pages       = new PerchContent_Pages;
    $Regions     = new PerchContent_Regions;
    $Collection  = false;

    $API  = new PerchAPI(1.0, 'content');
    $HTML = $API->get('HTML');

    $Lang   = $API->get('Lang');

    $pageID = false;
    $regionID = false;

    // Find the region
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Collection = $Collections->find($id);
        
    }

    if (!$Collection || !is_object($Collection)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }

    // App menu
    if ($Collection->collectionInAppMenu()) {
        $Perch = Perch::fetch();
        $Perch->set_section('collection:'.$Collection->collectionKey());    
    }
    
        
    // set the current user
    $Collection->set_current_user($CurrentUser->id());
    
    /* --------- Import Form ----------- */
    

    $Form = new PerchForm('import');
    
    if ($Form->posted() && $Form->validate()) {
        $postvars = array('pageID', 'regionID', 'go');
    	$data = $Form->receive($postvars);

        if (isset($data['pageID'])) {
            $pageID = $data['pageID'];
        }

        if (isset($data['regionID'])) {
            $regionID = $data['regionID'];
        }

        if ($pageID && $regionID) {

            $Region = $Regions->find($regionID);

            if (isset($data['go']) && $data['go']=='go') {

                // Run the import
                $Collection->import_from_region($Region);

                PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/collections/edit/?id='.$Collection->id());

            }


        }

       
    }


    $details = [];

