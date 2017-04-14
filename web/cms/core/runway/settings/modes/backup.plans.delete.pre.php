<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $planID  = (int) $_GET['id'];
        
        $Plans  = new PerchBackupPlans;
        $Plan = $Plans->find($planID);
        
    }

    if (!$Plans || !is_object($Plans)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/settings/backup/');
    }

    /* --------- Delete Form ----------- */
    
    $Form = $API->get('Form');
    $Form->set_name('delete');
    
    if ($Form->posted() && $Form->validate()) {
        
        $Plan->delete();
        
        if ($Form->submitted_via_ajax) {
            echo PERCH_LOGINPATH . '/core/settings/backup/';
            exit;
        }else{
            PerchUtil::redirect(PERCH_LOGINPATH . '/core/settings/backup/');
        }
                    
    }
