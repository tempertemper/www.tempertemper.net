<?php
    if (!PERCH_RUNWAY) exit;

    $Blogs = new PerchBlog_Blogs($API);

    $Form = $API->get('Form');

    $Form->set_name('delete');

    if (!$CurrentUser->has_priv('perch_blog.blogs.manage')) {
        PerchUtil::redirect($API->app_path());
    }

	$message = false;

	if (isset($_GET['id']) && $_GET['id']!='') {
	    $Blog = $Blogs->find($_GET['id']);
	}else{
	    PerchUtil::redirect($API->app_path());
	}


    if ($Form->submitted()) {

    	if (is_object($Blog)) {
    	    $Blog->delete();

            // clear the caches
            PerchBlog_Cache::expire_all();


    	    if ($Form->submitted_via_ajax) {
    	        echo $API->app_path().'/blogs/';
    	        exit;
    	    }else{
    	       PerchUtil::redirect($API->app_path().'/blogs/');
    	    }

        }else{
            $message = $HTML->failure_message('Sorry, that blog could not be deleted.');
        }
    }



    $details = $Blog->to_array();


