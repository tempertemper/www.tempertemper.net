<?php

    # Title panel
    if (is_object($Blog)) {
        $heading = $Lang->get('Editing ‘%s’ Blog', $HTML->encode($details['blogTitle']));
    }else{
        $heading = $Lang->get('Creating a New Blog');
    }

    echo $HTML->title_panel([
        'heading' => $heading,
    ], $CurrentUser);


    if ($message) echo $message;


    $template_help_html = $Template->find_help();
    if ($template_help_html) {
        echo $HTML->heading2('Help');
        echo '<div class="template-help">' . $template_help_html . '</div>';
    }


    echo $HTML->heading2('Blog details');


    echo $Form->form_start();

        echo $Form->text_field('blogTitle', 'Title', (isset($details['blogTitle']) ? $details['blogTitle'] : ''));

        $opts = array();
        if (PerchUtil::count($category_sets)) {
            foreach($category_sets as $Set) {
                $opts[] = array('value'=>$Set->setSlug(), 'label'=>$Set->setTitle());
            }
        }
        echo $Form->select_field('setSlug', 'Category set', $opts, (isset($details['setSlug']) ? $details['setSlug'] : ''));


        $opts = array();
        $Util = new PerchBlog_Util($API);
        $templates = $Util->find_templates();

        if (PerchUtil::count($templates)) {
            foreach($templates as $template) {
                $opts[] = array('value'=>$template, 'label'=>PerchUtil::filename($template, true));
            }
        }
        echo $Form->select_field('postTemplate', 'Default master template', $opts, (isset($details['postTemplate']) ? $details['postTemplate'] : 'post.html'));


        echo $Form->fields_from_template($Template, $details, $Blogs->static_fields);


        echo $Form->submit_field('btnSubmit', 'Save', $API->app_path().'/blogs/');


    echo $Form->form_end();
