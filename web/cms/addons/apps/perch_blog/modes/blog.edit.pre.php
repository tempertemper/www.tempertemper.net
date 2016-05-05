<?php
    if (!PERCH_RUNWAY) exit;

    $Blogs = new PerchBlog_Blogs($API);
    $CategorySets = new PerchCategories_Sets($API);

    $category_sets = $CategorySets->all();

    $HTML = $API->get('HTML');
    $Form = $API->get('Form');

    $message = false;

    if (!$CurrentUser->has_priv('perch_blog.blogs.manage')) {
        PerchUtil::redirect($API->app_path());
    }


    if (isset($_GET['id']) && $_GET['id']!='') {
        $blogID = (int) $_GET['id'];
        $Blog = $Blogs->find($blogID);
        $details = $Blog->to_array();
    }else{
        $blogID = false;
        $Blog = false;
        $details = array();
    }


    $Template   = $API->get('Template');
    $Template->set('blog/blog.html', 'blog');
    $Form->handle_empty_block_generation($Template);
    $tags = $Template->find_all_tags_and_repeaters();



    $Form->require_field('blogTitle', 'Required');
    $Form->set_required_fields_from_template($Template, $details);

    if ($Form->submitted()) {
		$postvars = array('blogTitle', 'setSlug', 'postTemplate');

    	$data = $Form->receive($postvars);

        $prev = false;

        if (isset($details['blogDynamicFields'])) {
            $prev = PerchUtil::json_safe_decode($details['blogDynamicFields'], true);
        }

        $dynamic_fields = $Form->receive_from_template_fields($Template, $prev, $Blogs, $Blog);
        $data['blogDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);

        if (!is_object($Blog)) {
            $data['blogSlug'] = PerchUtil::urlify($data['blogTitle']);
            $Blog = $Blogs->create($data);
            PerchUtil::redirect($API->app_path() .'/blogs/edit/?id='.$Blog->id().'&created=1');
        }


        $Blog->update($data);

        if (is_object($Blog)) {
            $message = $HTML->success_message('Your blog has been successfully edited. Return to %sblog listing%s', '<a href="'.$API->app_path() .'/blogs">', '</a>');
        }else{
            $message = $HTML->failure_message('Sorry, that blog could not be edited.');
        }

        // clear the caches
        PerchBlog_Cache::expire_all();

        $details = $Blog->to_array();
    }

    if (isset($_GET['created']) && !$message) {
        $message = $HTML->success_message('Your blog has been successfully created. Return to %sblog listing%s', '<a href="'.$API->app_path() .'/blogs">', '</a>');
    }
