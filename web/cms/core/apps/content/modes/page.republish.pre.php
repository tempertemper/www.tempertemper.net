<?php

	if (!$CurrentUser->has_priv('content.pages.republish')) {
		PerchUtil::redirect(PERCH_LOGINPATH);
	}

    $Pages      = new PerchContent_Pages;

    $Regions    = new PerchContent_Regions;
    
    $republish = false;

      
    $Form = new PerchForm('publish');
    
    if ($Form->posted() && $Form->validate()) {
        
        if ($CurrentUser->has_priv('content.pages.republish')) {
        	$republish = true;

        	$Alert->set('success', PerchLang::get('Republishing is underway.'));
        }
        
    
    }
?>