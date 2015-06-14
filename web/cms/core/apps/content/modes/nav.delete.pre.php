<?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {

        $NavGroups  = new PerchContent_NavGroups;
        $groupID = (int) $_GET['id'];    
        $NavGroup = $NavGroups->find($groupID);
    }

    
    if (!$NavGroup || !is_object($NavGroup)) {
        PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/navigation/');
    }

    /* --------- Delete Form ----------- */
    
    $Form = new PerchForm('delete');
    
    if ($Form->posted() && $Form->validate()) {
    	$NavGroup->delete();
    	
    	if ($Form->submitted_via_ajax) {
    	    echo PERCH_LOGINPATH . '/core/apps/content/navigation/';
    	    exit;
    	}else{
    	    PerchUtil::redirect(PERCH_LOGINPATH . '/core/apps/content/navigation/');
    	}
    	    	
    }

    

?>