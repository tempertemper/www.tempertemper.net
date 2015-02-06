<?php
	$default_fields = '<perch:categories id="setTitle" type="smarttext" label="Title" required="true" />
					   <perch:categories id="setSlug" type="slug" for="setTitle" />';

	$API = new PerchAPI(1.0, 'categories');
	$HTML = $API->get('HTML');

	$Sets 		= new PerchCategories_Sets;

	$setID   = false;
	$Set     = false;
	$message = false;
	$template = 'set.html';

	if (PerchUtil::get('id')) {
		$setID    = (int) PerchUtil::get('id');
		$Set      = $Sets->find($setID);
		$template = $Set->setTemplate();
	}

	if (!$CurrentUser->has_priv('categories.manage')) {
		PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/categories/');
	}

	$Template   = $API->get('Template');
	$Template->set('categories/'.$template, 'categories', $default_fields);

	$Form = $API->get('Form');
    $Form->set_required_fields_from_template($Template);

    if ($Form->submitted()) {		
    	
    	$data = $Form->get_posted_content($Template, $Sets, $Set, false);

        if (!is_object($Set)) {

            $Set = $Sets->create($data);
            PerchUtil::redirect(PERCH_LOGINPATH .'/core/apps/categories/sets/edit/?id='.$Set->id().'&created=1');
        }

        $Set->update($data);
    	
    	$Set->update_all_in_set();

        if (is_object($Set)) {
            $message = $HTML->success_message('Your set has been successfully edited. Return to %scategory listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/categories">', '</a>');
        }else{
            $message = $HTML->failure_message('Sorry, that set could not be edited.');
        }
        
    } 

    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your set has been successfully created. Return to %scategory listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/categories/">', '</a>');
    }
