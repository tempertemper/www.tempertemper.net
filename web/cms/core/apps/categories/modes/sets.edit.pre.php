<?php
	$default_fields = '<perch:categories id="setTitle" type="smarttext" label="Title" required="true" />
					   <perch:categories id="setSlug" type="slug" for="setTitle" />';



	$API  = new PerchAPI(1.0, 'categories');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');
	

	$Sets 		= new PerchCategories_Sets;

	$setID   = false;
	$Set     = false;
	$message = false;
	$details = array();
	$template = 'set.html';

	if (PerchUtil::get('id')) {
		$setID    = (int) PerchUtil::get('id');
		$Set      = $Sets->find($setID);
		$template = $Set->setTemplate();
		$details  = $Set->to_array();
	}

	if (!$CurrentUser->has_priv('categories.manage')) {
		PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/categories/');
	}

	$Template   = $API->get('Template');
	$Template->set('categories/'.$template, 'categories', $default_fields);
	$Template->disable_feature('categories');

	$Form = $API->get('Form');
	$Form->handle_empty_block_generation($Template);
    $Form->set_required_fields_from_template($Template, $details);

    if ($Form->submitted()) {

		$fixed_fields = $Form->receive(array('setTemplate', 'setCatTemplate'));	  	
    	$data = $Form->get_posted_content($Template, $Sets, $Set);
    	$data = array_merge($data, $fixed_fields);

        if (!is_object($Set)) {
            $Set = $Sets->create($data);
            PerchUtil::redirect(PERCH_LOGINPATH .'/core/apps/categories/sets/edit/?id='.$Set->id().'&created=1');
        }

        $Set->update($data);
    	
    	$Set->update_all_in_set();

        if (is_object($Set)) {
            $message = $HTML->success_message('Your set has been successfully edited. Return to %scategory listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/categories/sets/?id='.$Set->id().'" class="notification-link">', '</a>');
        }else{
            $message = $HTML->failure_message('Sorry, that set could not be edited.');
        }
        
    } 

    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your set has been successfully created. Return to %scategory listing%s', '<a href="'.PERCH_LOGINPATH .'/core/apps/categories/" class="notification-link">', '</a>');
    }
