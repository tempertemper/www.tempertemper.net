<?php 

    if (!PerchUtil::count($templates)) {
        $Alert->set('info', PerchLang::get('No master pages yet?') . ' ' .PerchLang::get('New master page templates can be added to the %stemplates/pages%s folder.', '<code>', '</code>'));
    }

    echo $HTML->title_panel([
    'heading' => $Lang->get('Listing all master pages'),
    ]);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => true,
        'title' => 'Master pages',
        'link'  => '/core/apps/content/page/templates/',
    ]);

    echo $Smartbar->render();

    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);

    $Listing->add_col([
            'title'     => 'Title',
            'value'     => function($item, $HTML){
                                $s = '';
                                if (strpos($item->templatePath(), '/')!==false) {
                                    $segments = explode('/', $item->templatePath());
                                    array_pop($segments);
                                    $s .= PerchUtil::filename(implode('/', $segments)).' → ';
                                }
                                $s .= $item->templateTitle();
                                return $s;
                            },
            'edit_link' => 'edit',
            'sort' => 'templateTitle',
        ]);

    $Listing->add_col([
            'title'     => 'Path',
            'value'     => 'templatePath',
            'sort'      => 'templatePath',
        ]);

    $Listing->add_delete_action([
            'priv'   => 'content.templates.delete',
            'inline' => true,
            'path'   => 'delete',
        ]);

    echo $Listing->render($templates);


