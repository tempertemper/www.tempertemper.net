<?php
	$Regions = new PerchContent_Regions;

	// Find the region
	if (isset($_POST['id']) && is_numeric($_POST['id'])) {
	    $id = (int) $_POST['id'];
	    $Region = $Regions->find($id);
	}

	// Check we have a region
	if (!$Region || !is_object($Region)) {
	    exit;
	}
	
	// Check permissions
	if (!$Region->role_may_edit($CurrentUser)) {
	   	exit;
	}

	if (isset($_POST['itm']) && is_numeric($_POST['itm'])) {
	    $item_id = (int) $_POST['itm'];
	}

    // set the current user
	$Region->set_current_user($CurrentUser->id());    

	// get Page
	$Pages = new PerchContent_Pages;
	$Page = $Pages->find($Region->pageID());

    if (!is_object($Page)) {
        $Page = $Pages->get_mock_shared_page();
    }

    $Form = new PerchForm('edit');

    $Template = new PerchTemplate('content/'.$Region->regionTemplate(), 'content');


