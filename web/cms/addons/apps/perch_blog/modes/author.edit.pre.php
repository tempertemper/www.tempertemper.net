<?php
    
    $Authors = new PerchBlog_Authors($API);

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');
	
    $message = false;

    if (!$CurrentUser->has_priv('perch_blog.authors.manage')) {
        PerchUtil::redirect($API->app_path());
    }
    
    
    if (isset($_GET['id']) && $_GET['id']!='') {
        $authorID = (int) $_GET['id'];    
        $Author = $Authors->find($authorID);
        $details = $Author->to_array();
    }else{
        $message = $HTML->failure_message('Sorry, that author could not be updated.');
        $details = false;
    }
    

    $Template   = $API->get('Template');
    $Template->set('blog/author.html', 'blog');

    $Form->handle_empty_block_generation($Template);

    $tags = $Template->find_all_tags_and_repeaters();
    
    $Form->require_field('authorGivenName', 'Required');
    $Form->require_field('authorEmail', 'Required');
    $Form->set_required_fields_from_template($Template, $details);

    if ($Form->submitted()) {
		$postvars = array('authorGivenName', 'authorFamilyName', 'authorEmail', 'authorSlug');
		
    	$data = $Form->receive($postvars);

        $prev = false;

        if (isset($details['authorDynamicFields'])) {
            $prev = PerchUtil::json_safe_decode($details['authorDynamicFields'], true);
        }

        $dynamic_fields = $Form->receive_from_template_fields($Template, $prev, $Authors, $Author);
        $data['authorDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);
    	
        $Author->update($data);

        $Author->index($Template);
    	
        if (is_object($Author)) {
            $message = $HTML->success_message('The author has been successfully edited. Return to %sauthor listing%s', '<a href="'.$API->app_path() .'/authors">', '</a>');
        }else{
            $message = $HTML->failure_message('Sorry, that author could not be edited.');
        }
        
        // clear the caches
        PerchBlog_Cache::expire_all();
        
        $details = $Author->to_array();
    }

    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('The author has been successfully created. Return to %sauthor listing%s', '<a href="'.$API->app_path() .'/authors">', '</a>');
    }

