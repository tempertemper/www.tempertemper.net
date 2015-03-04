<?php

    $Regions = new PerchContent_Regions;
    $Region  = false;
    
    $item_id = false;

    // Find the region
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Region = $Regions->find($id);
    }
    
    // Check we have a region
    if (!$Region || !is_object($Region)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/');
    }
    
    // Check permissions
    if (!$Region->role_may_edit($CurrentUser)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/edit/denied/');
    }

    
    if ($Region->regionTemplate()=='') {
        $mode = 'region.define';
        include __DIR__.'/'.$mode.'.pre.php';
    }
    
    
    
    
    if ($Region->regionTemplate()!='') {
        
        $mode = 'edit.form';
        
        if ($Region->regionMultiple()) {
            switch($Region->get_option('edit_mode')) {

                case 'singlepage':
                    $mode = 'edit.form';
                    break;

                case 'listdetail':
                    $mode = 'region.itemlist';            
                    break;

                default:
                    $mode = 'edit.form';
                    break;

            }
        }
        

        
        if (isset($_GET['itm']) && is_numeric($_GET['itm'])) {
            $item_id = (int) $_GET['itm'];
            $mode = 'edit.form'; 
        }

        include __DIR__.'/'.$mode.'.pre.php';
    }
