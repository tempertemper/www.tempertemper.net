<?php
    if (!$CurrentUser->has_priv('perch_blog.import')) {
        PerchUtil::redirect($API->app_path());
    }

    $Posts = new PerchBlog_Posts($API);
    $Blogs = new PerchBlog_Blogs($API);
    $blogs = $Blogs->all();

    $Blog = false;

    if (!$Blog) {
        $Blog = $Blogs->first();
    }

    $BlogUtil = new PerchBlog_Util($API);

    $files = $BlogUtil->find_importable_files();
    
   	$Form = $API->get('Form');

   	
    $Form->require_field('file', 'Required');

    if ($Form->submitted()) {
    	        
        $postvars = array('file','format','type', 'section');
		
    	$data = $Form->receive($postvars);

    	switch($data['type']) {

    		case 'wordpress':
    			PerchUtil::redirect($API->app_path().'/import/wordpress?'.http_build_query($data));
    			break;

            case 'posterous':
                PerchUtil::redirect($API->app_path().'/import/posterous?'.http_build_query($data));
                break;

    	}

    }
