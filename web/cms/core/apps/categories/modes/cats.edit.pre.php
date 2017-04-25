<?php

		$API  = new PerchAPI(1.0, 'categories');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');


	/*
		DEFAULT FIELDS
		This is a string of template code that gets appended to the end of the template.
		It's used to ensure that any required static fields end up in the form even if the user removes them.
		Useful for things like titles and slugs that the app won't work without.
	 */
	$default_fields = '<perch:category id="catTitle" type="smarttext" label="Title" required="true" />
					   <perch:category id="catSlug" type="slug" for="catTitle" />';


	$Sets 		= new PerchCategories_Sets;
	$Categories = new PerchCategories_Categories($API);

	/*
		Initialising variables we need to set the default for our different edit states.
		This page edits in the following states:

			1) Edit - a category ID is passed in ?id=
			2) New in set - a set ID is passed in ?sid=
			3) New subcat - a parent category ID is passed in ?pid=

	 */
	$catID     = false;
	$Category  = false;
	$Set       = false;
	$ParentCat = false;

	// Success or failure message
	$message = false;

	$details = array();

	/*
		Figure out what mode we're in by testing what's on the query string.
		Load up instances of the Category and Set where appropriate.
	 */

	if (PerchUtil::get('id')) {
		// Edit mode
		$catID = (int) PerchUtil::get('id');
		$Category = $Categories->find($catID);
		$Set = $Sets->find($Category->setID());
		$details = $Category->to_array();

	}else{
		// New category mode
		if (PerchUtil::get('sid')) {
			// New in set
			$Set = $Sets->find((int)PerchUtil::get('sid'));
		}

		if (PerchUtil::get('pid')) {
			// New based on parent category
			$ParentCat = $Categories->find((int)PerchUtil::get('pid'));
			$Set = $Sets->find($ParentCat->setID());
		}
	}


	/*
		Check permissions and that we have enough data to proceed.
		If not, redirect back to the listing.
		This state wouldn't normally be hit - this is a safety valve.
	*/
	if (!$Set || !$CurrentUser->has_priv('categories.manage')) {
		PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/categories/');
	}

	/*
		TEMPLATE
		Load up the master template that is used for editing.
		Set a namespace.
		Append those default fields.
	 */

	$Template   = $API->get('Template');
	$Template->set('categories/'.$Set->setCatTemplate(), 'category', $default_fields);

	/*
		FORM
		Set up the form and pass in the required fields from the template (for validation)

	 */
	$Form = $API->get('Form');
	$Form->handle_empty_block_generation($Template);
    $Form->set_required_fields_from_template($Template, $details);




    /*
    	FORM HANDLER
    	Check to see if the form has been submitted.
    	If so, go ahead and process it.
     */
    if ($Form->submitted()) {		
    	

    	/*
    		Read in the posted content into a variable $data
    	 */
    	$data = $Form->get_posted_content($Template, $Categories, $Category);

    	/*
    		If this is a new category, create it.
    	 */
        if (!is_object($Category)) {

        	$data['setID'] = $Set->id();

        	if ($ParentCat) {
        		$data['catParentID'] = $ParentCat->id();
        	}else{
        		$data['catParentID'] = '0';
        	}

            $Category = $Categories->create($data);

            if ($Category) {

	            // Add another mode? If so, redirect to a refreshed edit page
	            if ($Form->submitted_with_add_another()) {
	            	$pid = '';
	            	if ($Category->catParentID()) {
	            		$pid = '&pid='.$Category->catParentID();	
	            	}

	            	PerchUtil::redirect(PERCH_LOGINPATH .'/core/apps/categories/edit/?sid='.$Set->id().'&created=1'.$pid);
	            	
	            }

	            // Redirect into edit mode to make sure the ID is on the query string for subsequent edits.
	            // Makes things much cleaner and eliminates heaps of potential bugs.
	            PerchUtil::redirect(PERCH_LOGINPATH .'/core/apps/categories/edit/?id='.$Category->id().'&created=1');

	        }else{

	        	$message = $HTML->failure_message('Sorry, that category could not be created.');

	        }
        }


        if (is_object($Category)) {

	        /*
	        	If this is an existing category, update it.
	         */
	        $Category->update($data);
	    	

	    	/*
	    		Update the entire set    	
	    	 */
	    	$Set->update_all_in_set();

	        /*
	        	Add another?        
	         */
	        if ($Form->submitted_with_add_another()) {
	        	$pid = '';
	        	if ($Category->catParentID()) {
	        		$pid = '&pid='.$Category->catParentID();	
	        	}
	        	PerchUtil::redirect(PERCH_LOGINPATH .'/core/apps/categories/edit/?sid='.$Set->id().'&edited=1'.$pid);
	        }

	    }


        /*
        	Create a success or failure message.
         */
        if (is_object($Category)) {
            $message = $HTML->success_message('Your category has been successfully edited. Return to %scategory listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/categories" class="notification-link">', '</a>');
        }else{
            $message = $HTML->failure_message('Sorry, that category could not be edited.');
        }
        
    } // Form handler ends




    /*
    	If we just created a new category, the page will load fresh with ?created= on the query string.
    	Pick that up and show a message saying the category was created.
     */
    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your category has been successfully created. Return to %scategory listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/categories/sets/?id='.$Set->id().'" class="notification-link">', '</a>');
    }

    /*
    	If we just edited a category and chose to add another, the page will load fresh with ?edited= on the query string.
    	Pick that up and show a message saying the category was edited.
     */
    if (isset($_GET['edited']) && !$message) {
        $message = $HTML->success_message('Your category has been successfully edited. Return to %scategory listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/categories" class="notification-link">', '</a>');
    }
