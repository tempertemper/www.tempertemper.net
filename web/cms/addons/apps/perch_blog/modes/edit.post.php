<?php

    if (is_object($Post)) {
        $heading = $Lang->get('Editing Post ‘%s’', $HTML->encode($Post->postTitle()));
    }else{
        $heading = $Lang->get('Creating a New Post');
    }

    echo $HTML->title_panel([
            'heading' => $heading,
            ], $CurrentUser);


    include(__DIR__.'/_post_smartbar.php');

    if ($edit_mode=='define') {
        include('edit.define.post.php');
    }else{


        $template_help_html = $Template->find_help();
        if ($template_help_html) {
            echo $HTML->heading2('Help');
            echo '<div class="template-help">' . $template_help_html . '</div>';
        }

        if ($template =='post.html') {
            echo $HTML->heading2('Post');
        }else{
            echo '<h2 class="divider"><div>'.$HTML->encode(PerchUtil::filename($template, false)).'</div></h2>';
        }


        /* ---- FORM ---- */
        $lock_key = null;
        if (is_object($Post)) {
            $lock_key = 'blogpost:'.$Post->id();
        }
        echo $Form->form_start('blog-edit', '', $lock_key);


            /* ---- FIELDS FROM TEMPLATE ---- */
            $modified_details = $details;

            if (isset($modified_details['postDescRaw'])) {
                $modified_details['postDescHTML'] = $modified_details['postDescRaw'];
            }

            echo $Form->fields_from_template($Template, $modified_details);


            /* ---- TAGS ---- */
            #echo $Form->hint('Separate with commas');
            #echo $Form->text_field('postTags', 'Tags', isset($details['postTags'])?$details['postTags']:false);


            /* ---- COMMENTS ---- */
            #if ($CurrentUser->has_priv('perch_blog.comments.enable')) {
            #    echo $Form->checkbox_field('postAllowComments', 'Allow comments', '1', isset($details['postAllowComments'])?$details['postAllowComments']:'1');
            #}


            /* ---- POST TEMPLATES} ---- */
           #if (PerchUtil::count($post_templates)) {
           #    $opts = array();
           #    $opts[] = array('label'=>$Lang->get('Default'), 'value'=>'post.html');

           #    foreach($post_templates as $template) {
           #        $opts[] = array('label'=>PerchUtil::filename($template, false), 'value'=>'posts/'.$template);
           #    }
           #    echo $Form->hint('See sidebar note about post types');
           #    echo $Form->select_field('postTemplate', 'Post type', $opts, isset($details['postTemplate'])?$details['postTemplate']:'post.html');

           #}else{
               echo $Form->hidden('postTemplate', isset($details['postTemplate'])?$details['postTemplate']:$template);
           #}


            /* ---- AUTHORS ---- */
            #$authors = $Authors->all();
            #if (PerchUtil::count($authors)) {
            #    $opts = array();
            #    foreach($authors as $author) {
            #        $opts[] = array('label'=>$author->authorGivenName().' '.$author->authorFamilyName(), 'value'=>$author->id());
            #    }
            #    echo $Form->select_field('authorID', 'Author', $opts, isset($details['authorID'])?$details['authorID']:$Author->id());
            #}

            /* ---- SECTIONS ---- */
            #if (PerchUtil::count($sections)>1) {
            #    $opts = array();
            #    foreach($sections as $section) {
            #        $opts[] = array('label'=>$section->sectionTitle(), 'value'=>$section->id());
            #    }
            #    echo $Form->select_field('sectionID', 'Section', $opts, isset($details['sectionID'])?$details['sectionID']:1);
            #}


            /* ---- PUBLISHING ---- */


            if (is_object($Post)) {

                $opts = array();
                $opts[] = array('label'=>$Lang->get('Draft'), 'value'=>'Draft');
                if ($CurrentUser->has_priv('perch_blog.post.publish')) $opts[] = array('label'=>$Lang->get('Published'), 'value'=>'Published');
                echo $Form->select_field('postStatus', 'Status', $opts, isset($details['postStatus'])?$details['postStatus']:'Draft');

                echo $Form->submit_field('btnSubmit', 'Save', $API->app_path());

            } else {

                echo $Form->hidden('authorID', $Author->id());
                echo $Form->hidden('postStatus', 'Draft');
                echo $Form->submit_field('btnSubmit', 'Create draft', $API->app_path());
            }


            

        echo $Form->form_end();
        /* ---- /FORM ---- */

    } // if edit_mode