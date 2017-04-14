<?php
    $app_settings   = $Perch->get_settings();
    
    if (PerchUtil::count($app_settings)) {
        foreach($app_settings as $id=>$setting) {
           $postvars[] = $id;
           
           if ($setting['type'] == 'checkbox') {
               $checkboxes[] = $id;
           }
        }
    }