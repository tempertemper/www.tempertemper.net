<?php

	if (!isset($smartbar_selection)) {
		$smartbar_selection = 'details';
	}

    if (is_object($Post)) {

        $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

        $Smartbar->add_item([
                'active' => ($smartbar_selection=='details'),
                'type'  => 'breadcrumb',
                'links' => [
                    [
                        'title' => $Blog->blogTitle(),
                        'link'  => $API->app_nav('perch_blog').'/?blog='.$Blog->blogSlug(),
                        'translate' => false,
                    ],
                    [
                        'title' => $Post->postTitle(),
                        'link'  => $API->app_nav('perch_blog').'/edit/?id='.$Post->id(),
                        'translate' => false,
                    ]
                ],
            ]);

        $Smartbar->add_item([
                'active' => ($smartbar_selection=='meta'),
                'link' => $API->app_nav('perch_blog').'/meta/?id='.$Post->id(),
                'title' => 'Meta and Social',
                'icon'  => 'core/o-toggles',
            ]);

        $Smartbar->add_item([
                'active'        => false,
                'link'          => ($draft ? $Post->previewURL() : $Post->postURL()),
                'title'         => ($draft ? 'Preview Draft' : 'View Post'),
                'link-absolute' => true,
                'new-tab'       => true,
                'icon'          => 'core/document',
                'position'      => 'end',
            ]);

        echo $Smartbar->render();

    }

