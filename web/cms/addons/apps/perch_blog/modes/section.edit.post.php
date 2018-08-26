<?php

    # Title panel
    if (is_object($Section)) {
        $heading = $Lang->get('Editing ‘%s’ Section', $HTML->encode($details['sectionTitle']));
    }else{
        $heading = $Lang->get('Creating a New Section');
    }

    echo $HTML->title_panel([
            'heading' => $heading,
            ], $CurrentUser);

    if ($message) echo $message;

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'type' => 'breadcrumb',
        'links' => [
            [
                'title' => $Blog->blogTitle(),
                'translate' => false,
                'link' => $API->app_nav('perch_blog').'/sections/?blog='.$Blog->blogSlug()
            ],
            [
                'title' => (is_object($Section) ? $Section->sectionTitle() : $Lang->get('New Section')),
                'translate' => false,
                'link' => $API->app_nav('perch_blog').'/sections/edit/'. (is_object($Section) ? '?id='.$Section->id() : '?blog='.$Blog->blogID())
            ]
        ],
        ]);

    echo $Smartbar->render();

    $template_help_html = $Template->find_help();
    if ($template_help_html) {
        echo $HTML->heading2('Help');
        echo '<div class="template-help">' . $template_help_html . '</div>';
    }


    echo $HTML->heading2('Section details');


    echo $Form->form_start();

        echo $Form->text_field('sectionTitle', 'Title', (isset($details['sectionTitle']) ? $details['sectionTitle'] : ''));
		echo $Form->hidden('sectionID', (isset($details['sectionID']) ? $details['sectionID'] : ''));

        echo $Form->fields_from_template($Template, $details, $Sections->static_fields);


        echo $Form->submit_field('btnSubmit', 'Save', $API->app_path().'/sections/');


    echo $Form->form_end();

 