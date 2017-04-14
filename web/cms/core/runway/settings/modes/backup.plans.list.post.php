<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Configuring backup plans'),
            'button'  => [
                        'text' => $Lang->get('Add plan'),
                        'link' => '/core/settings/backup/edit/',
                        'icon' => 'core/plus',
                        'priv' => 'perch.backups.manage',
                    ]
        ], $CurrentUser);

	   
    if (!$errors) {

        $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

        $Smartbar->add_item([
                'title' => 'Plans',
                'link'  => '/core/settings/backup/',
                'active' => true,
                'icon'  => 'ext/o-umbrella',
            ]);

        $Smartbar->add_item([
                'title' => 'Restore',
                'link'  => '/core/settings/backup/restore/general/',
                'position' => 'end',
                'icon' => 'core/o-backup'
            ]);

        echo $Smartbar->render();
    }


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Title',
            'value'     => 'planTitle',
            'sort'      => 'planTitle',
            'edit_link' => '../backup',
        ]);
    $Listing->add_col([
            'title'     => 'Frequency',
            'value'     => 'planFrequency',
            'sort'      => 'planFrequency',
        ]);

    $Listing->add_col([
            'title'     => 'Last run',
            'value'     => 'last_run_date_for_display',
        ]);

    $Listing->add_col([
            'title'     => 'Active',
            'type'      => 'status',
            'value'     => 'planActive',
            'sort'      => 'planActive',
        ]);
    
    $Listing->add_delete_action([
            'priv'   => 'perch.backups.manage',
            'inline' => true,
            'path'   => 'delete'
        ]);

    echo $Listing->render($plans);
