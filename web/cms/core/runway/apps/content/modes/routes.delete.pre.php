<?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $routeID  = (int) $_GET['id'];
        
        $Routes  = new PerchPageRoutes;
        $Route = $Routes->find($routeID);
        
    }

    if (!$Routes || !is_object($Routes)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/routes/');
    }

    /* --------- Delete Form ----------- */
    
    $Form = new PerchForm('delete');
    
    if ($Form->posted() && $Form->validate()) {
        
        $Route->delete();
        
        if ($Form->submitted_via_ajax) {
            echo PERCH_LOGINPATH . '/core/apps/content/routes/';
            exit;
        }else{
            PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/routes/');
        }
                    
    }
