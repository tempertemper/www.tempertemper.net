<?php

	if (!$CurrentUser->has_priv('content.pages.republish')) {
		PerchUtil::redirect(PERCH_LOGINPATH);
	}


    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    $Pages      = new PerchContent_Pages;

    $Regions    = new PerchContent_Regions;
    
    $republish = false;

      
    $Form = $API->get('Form');
    
    if ($Form->posted() && $Form->validate()) {
        
        if ($CurrentUser->has_priv('content.pages.republish')) {
        	$republish = true;

        	$Alert->set('success', PerchLang::get('Republishing is under way.'));
        }
        
    
    }
