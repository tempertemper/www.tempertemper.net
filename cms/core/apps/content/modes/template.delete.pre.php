<?php
    
    $Templates  = new PerchContent_PageTemplates;
    $Pages      = new PerchContent_Pages;

    $Form = new PerchForm('edit');
	
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
    
    
    $Form = new PerchForm('rm');

    
    if ($Form->posted() && $Form->validate()) {
    
		$Template->delete();
        PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/content/page/templates/');

    }
    
    
    
    $details = $Template->to_array();

?>