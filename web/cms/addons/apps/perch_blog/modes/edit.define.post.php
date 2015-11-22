<?php
	echo $HTML->heading2('Choose a post type');

	echo $DefineForm->form_start('blog-post-type', 'magnetic-save-bar');

            /* ---- POST TEMPLATES} ---- */
           if (PerchUtil::count($post_templates)) {
               $opts = array();
               $opts[] = array('label'=>$Lang->get('Default'), 'value'=>'post.html');

               foreach($post_templates as $template) {
                   $opts[] = array('label'=>PerchUtil::filename($template, false), 'value'=>'posts/'.$template);
               }
               echo $DefineForm->select_field('postTemplate', 'Post type', $opts, isset($details['postTemplate'])?$details['postTemplate']:'post.html');

           }

            echo $DefineForm->submit_field('btnSubmit', 'Save', $API->app_path());

        echo $DefineForm->form_end();