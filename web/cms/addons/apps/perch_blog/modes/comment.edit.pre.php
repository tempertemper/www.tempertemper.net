<?php
    
    $HTML = $API->get('HTML');

	$Form = $API->get('Form');
    $Posts = new PerchBlog_Posts($API);

    $message = false;

    $Comments = new PerchBlog_Comments($API);


    if (!$CurrentUser->has_priv('perch_blog.comments.moderate')) {
        PerchUtil::redirect($API->app_path());
    }


     if (isset($_GET['id']) && $_GET['id']!='') {
         $commentID = (int) $_GET['id'];    
         $Comment = $Comments->find($commentID);
         $details = $Comment->to_array();
         $Post    = $Posts->find($Comment->postID());
     }else{
         $message = $HTML->failure_message('Sorry, that comment could not be found.');
         $details = array();
     }


    $Template   = $API->get('Template');
    $Template->set('blog/comment.html', 'blog');

    $Form->handle_empty_block_generation($Template);
    
    $tags = $Template->find_all_tags_and_repeaters();

    $Form->set_required_fields_from_template($Template, $details);

     if ($Form->submitted()) {
 		$postvars = array('perch_commentName', 'perch_commentEmail', 'perch_commentHTML', 'commentStatus', 'perch_commentDateTime', 'perch_commentURL');

     	$data = $Form->receive($postvars);
        $data['perch_commentDateTime'] = $Form->get_date('perch_commentDateTime');

        if (PerchUtil::count($data)) 
        foreach($data as $key=>$val) {
            if (strpos($key, 'perch_')===0) {
                $data[str_replace('perch_', '', $key)] = $val;
                unset($data[$key]);
            }
        }

        $dynamic_fields = $Form->receive_from_template_fields($Template, $details, $Comments, $Comment);
        $data['commentDynamicFields'] = PerchUtil::json_safe_encode($dynamic_fields);

        if ($Comment->commentStatus()!=$data['commentStatus']) {
            // status has changed
            
            // was the comment live? If so update the post's comment count.
            if ($Comment->commentStatus()=='LIVE') {
                $Post = $Posts->find($Comment->postID());
                if ($Post) $Post->update_comment_count();
            }


            $Comment->set_status($data['commentStatus']);           
            
        }

        $Comment->update($data);

        if (is_object($Comment)) {
            $message = $HTML->success_message('The comment has been successfully edited.');
        }else{
            $message = $HTML->failure_message('Sorry, that comment could not be edited.');
        }

        if ($Form->submitted_with_add_another()) {
            // find the next unmoderated
            $NextComment = $Comments->get_first_pending($Comment->id());
            if ($NextComment) {
                PerchUtil::redirect($API->app_path().'/comments/edit/?id='.$NextComment->id());
            }else{
                PerchUtil::redirect($API->app_path().'/comments/');
            }
        }

        
        
     }

     $details = $Comment->to_array();
 