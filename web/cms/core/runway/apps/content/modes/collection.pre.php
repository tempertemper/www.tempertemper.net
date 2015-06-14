<?php

    $Collections = new PerchContent_Collections;
    $Items = new PerchContent_CollectionItems;
    $Collection  = false;
    
    $item_id = false;

    // Find the collection
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Collection = $Collections->find($id);
    }
    

    $mode = 'collection.list';

    if (is_object($Collection)) {


        $Perch->page_title = $Collection->collectionKey();



        if ($Collection->collectionInAppMenu()) {
            $Perch = Perch::fetch();
            $Perch->set_section('collection:'.$Collection->collectionKey());    
        }
        
        
        // Check permissions
        if (!$Collection->role_may_edit($CurrentUser)) {
            PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/edit/denied/');
        }

        
        if ($Collection->collectionTemplate()=='') {
            $mode = 'collection.define';
            include $mode.'.pre.php';
        }
           
        
        if ($Collection->collectionTemplate()!='') {
            
            $mode = 'collection.items';

            if (isset($_GET['itm']) && is_numeric($_GET['itm'])) {
                $item_id = (int) $_GET['itm'];
                $mode = 'collection.edit.form'; 
            }
                
           
        }
    }


    include $mode.'.pre.php';

