<?php

    $API    = new PerchAPI(1.0, 'content');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    $Paging = $API->get('Paging');
    
    $Templates  = new PerchContent_PageTemplates;
    $Pages      = new PerchContent_Pages;

	
    $message = false;

    // Check permissions
    if (!$CurrentUser->has_priv('content.templates.delete')) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/pages/templates/');
    }
        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $templateID = (int) $_GET['id'];    
        $Template = $Templates->find($templateID);
    }else{
        PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/page/templates/');
    }
    
    
    $Form = $API->get('Form');
    $Form->set_name('delete');

    
    if ($Form->posted() && $Form->validate()) {
    
		$Template->delete();

        if ($Form->submitted_via_ajax) {
            echo PERCH_LOGINPATH.'/core/apps/content/page/templates/';
            exit;
        }else{
           PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/page/templates/'); 
        }

    }
    
    
    
    $details = $Template->to_array();

