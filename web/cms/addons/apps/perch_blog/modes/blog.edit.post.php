<?php

    # Side panel
    echo $HTML->side_panel_start();

    echo $HTML->para('Give the blog a new name.');

    echo $HTML->side_panel_end();


    # Main panel
    echo $HTML->main_panel_start();

    include('_subnav.php');


    # Title panel
    if (is_object($Blog)) {
        echo $HTML->heading1('Editing ‘%s’ Blog', $details['blogTitle']);
    }else{
        echo $HTML->heading1('Creating a New Blog');
    }

    if ($message) echo $message;


    $template_help_html = $Template->find_help();
    if ($template_help_html) {
        echo $HTML->heading2('Help');
        echo '<div id="template-help">' . $template_help_html . '</div>';
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

    echo $HTML->main_panel_end();
