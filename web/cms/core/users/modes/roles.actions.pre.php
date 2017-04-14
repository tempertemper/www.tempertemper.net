<?php
    #include(PERCH_PATH.'/core/apps/content/PerchContent_Regions.class.php');
    #include(PERCH_PATH.'/core/apps/content/PerchContent_Region.class.php');
    #include(PERCH_PATH.'/core/apps/content/PerchContent_Pages.class.php');
    #include(PERCH_PATH.'/core/apps/content/PerchContent_Page.class.php');

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    



	$Roles   = new PerchUserRoles; 
	$Regions = new PerchContent_Regions;
	$Pages   = new PerchContent_Pages;



    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = (int) $_GET['id'];
        $Role = $Roles->find($id);
    }else{
        $id = false;
        $Role = false;
    }

    if (!is_object($Role)) {
    	PerchUtil::redirect(PERCH_LOGINPATH.'/core/users/roles/');
    }


    $Form 	= new PerchForm('action', false);

    if ($Form->posted() && $Form->validate()) {

    	$action = false;

    	if (isset($_POST['regions']) && $_POST['regions']!='' && $_POST['regions']!='noaction') {

    		if ($_POST['regions'] == 'grant') {
    			$Regions->modify_permissions('grant', $Role->id());
    		}

			if ($_POST['regions'] == 'revoke') {
    			$Regions->modify_permissions('revoke', $Role->id());
    		}

    		$action = true;
    	}


    	if (isset($_POST['pages']) && $_POST['pages']!='' && $_POST['pages']!='noaction') {

    		if ($_POST['pages'] == 'grant') {
    			$Pages->modify_subpage_permissions('grant', $Role->id());
    		}

			if ($_POST['pages'] == 'revoke') {
    			$Pages->modify_subpage_permissions('revoke', $Role->id());
    		}

    		$action = true;

    	}

    	if ($action) {
    		$Alert->set('success', PerchLang::get('Those changes have been made successfully.'));
    	}else{
    		$Alert->set('success', PerchLang::get('You chose to make no changes. No changes have made successfully. (It was easy).'));
    	}

   	}
