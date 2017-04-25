<?php
    $Blogs = new PerchBlog_Blogs($API);
    $Posts = new PerchBlog_Posts($API);
    $message = false;

    $Authors = new PerchBlog_Authors;
    $Author = $Authors->find_or_create($CurrentUser);

    if (!$CurrentUser->has_priv('perch_blog.post.create')) {
        PerchUtil::redirect($API->app_path());
    }

    if (isset($_GET['id']) && $_GET['id']!='') {
        $postID   = (int) $_GET['id'];
        $Post     = $Posts->find($postID, true);
        $details  = $Post->to_array();
        $template = $Post->postMetaTemplate();

    }

    $Blog = false;

    if (PERCH_RUNWAY) {
        if ($Post) {
            $Blog = $Post->get_blog();
        }else{
            if (PerchUtil::get('blog')) {
                $Blog = $Blogs->find((int)PerchUtil::get('blog'));
            }
        }
    }
    if (!$Blog) {
        $Blog = $Blogs->find(1);
    }

    $Sections = new PerchBlog_Sections;
    $sections = $Sections->get_by('blogID', $Blog->id());

    if (!$template) {
        $template = $Blog->postMetaTemplate();
    }

    $Template   = $API->get('Template');
    $Template->set('blog/'.$template, 'blog');

    $tags = $Template->find_all_tags_and_repeaters();

    $Form = $API->get('Form');

    $Form->handle_empty_block_generation($Template);

    $result = false;

    $Form->set_required_fields_from_template($Template, $details);

    if ($Form->submitted()) {

        $postvars = array('postTags', 'postAllowComments', 'postTemplate', 'authorID', 'sectionID');

    	$data = $Form->receive($postvars);

        if (!isset($data['postAllowComments'])) {
            $data['postAllowComments']  = '0';
        }

        /*
            Don't copy this, or try to upgrade it.
            Legacy, legacy, legacy, legacy, mushroom, mushroom.
         */

        $prev = false;

        if (isset($details['postDynamicFields'])) {
            $prev = PerchUtil::json_safe_decode($details['postDynamicFields'], true);
        }

    	$dynamic_fields = $Form->receive_from_template_fields($Template, $prev, $Posts, $Post, $clear_post=true, $strip_static_fields=false);

       # PerchUtil::debug('Dynamic fields:');
       # PerchUtil::debug($dynamic_fields);

        // fetch out static fields
        
        if (isset($dynamic_fields['postDescHTML']) && is_array($dynamic_fields['postDescHTML'])) {
            $data['postDescRaw']  = $dynamic_fields['postDescHTML']['raw'];
            $data['postDescHTML'] = $dynamic_fields['postDescHTML']['processed'];
            unset($dynamic_fields['postDescHTML']);
        }

        if (isset($dynamic_fields['postURL'])) {
            unset($dynamic_fields['postURL']);
        }

        foreach($Posts->static_fields as $field) {
            if (isset($dynamic_fields[$field])) {

                if (is_array($dynamic_fields[$field])) {
                    if (isset($dynamic_fields[$field]['_default'])) {
                        $data[$field] = trim($dynamic_fields[$field]['_default']);
                    }

                    if (isset($dynamic_fields[$field]['processed'])) {
                        $data[$field] = trim($dynamic_fields[$field]['processed']);
                    }
                }

                if (!isset($data[$field])) $data[$field] = $dynamic_fields[$field];
                unset($dynamic_fields[$field]);
            }
        }

    	$data['postDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);

        if (!$CurrentUser->has_priv('perch_blog.post.publish')) {
            $data['postStatus'] = 'Draft';
        }
        

    	if (is_object($Post)) {

            #PerchUtil::debug($data, 'notice');
    	    $Post->Template = $Template;
    	    $result = $Post->update_meta($data);

            $Post->index($Template);
            
    	}


        if ($result) {
            $message = $HTML->success_message('Your post has been successfully updated. Return to %spost listing%s', '<a href="'.$API->app_path() .'">', '</a>');
        }else{
            $message = $HTML->failure_message('Sorry, that post could not be updated.');
        }

        $details = $Post->to_array();

        // clear the caches
        PerchBlog_Cache::expire_all();

        // update category post counts;
        $Posts->update_category_counts();
        $Authors->update_post_counts();
        $Sections->update_post_counts();


        // Has the template changed? If so, need to redirect back to kick things off again.
        if ($data['postTemplate'] != $template) {
            #PerchUtil::redirect($API->app_path() .'/edit/?id='.$Post->id().'&edited=1');
        }

    }

    if (isset($_GET['edited']) && !$message) {
        $message = $HTML->success_message('Your post has been successfully updated. Return to %spost listing%s', '<a href="'.$API->app_path() .'">', '</a>');
    }


    // is it a draft?
    if (is_object($Post) && $Post->postStatus()=='Draft') {
        $draft = true;
        $message = $Lang->get('%sYou are editing a draft. %sPreview%s', '<p class="alert draft">', '<a href="'.$HTML->encode($Post->previewURL()).'" class="action draft-preview">', '</a></p>');
    }else{
        $draft = false;
        $url   = false;
    }

    $post_templates = PerchUtil::get_dir_contents(PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/blog/posts'), false);