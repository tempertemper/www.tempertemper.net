<?php
	$Collections = new PerchContent_Collections();
	$Items       = new PerchContent_CollectionItems();
	$Regions     = new PerchContent_Regions();

	$Collection = false;

	// Find the region
	if (isset($_POST['id']) && is_numeric($_POST['id'])) {
	    $id = (int) $_POST['id'];
	    $Collection = $Collections->find($id);
	}

	// Check we have a region
	if (!$Collection || !is_object($Collection)) {
	    exit;
	}
	
	// Check permissions
	if (!$Collection->role_may_edit($CurrentUser)) {
	   	exit;
	}

	if (isset($_POST['itm']) && is_numeric($_POST['itm'])) {
	    $item_id = (int) $_POST['itm'];
	}

    // set the current user
	$Collection->set_current_user($CurrentUser->id());     

    $Form = new PerchForm('edit');

    $Template = new PerchTemplate('content/'.$Collection->collectionTemplate(), 'content');


