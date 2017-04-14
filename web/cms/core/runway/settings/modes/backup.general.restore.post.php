<?php 
    echo $HTML->title_panel([
        'heading' => 'Restore from backup',
        ]); 

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

        $Smartbar->add_item([
                'title' => 'Plans',
                'link'  => '/core/settings/backup/',
                'icon'  => 'ext/o-umbrella',
            ]);

        $Smartbar->add_item([
                'title' => 'Restore',
                'link'  => '/core/settings/backup/restore/general/',
                'position' => 'end',
                'icon' => 'core/o-backup',
                'active' => true,
            ]);

        echo $Smartbar->render();

        echo $HTML->open('div.inner');

    /*
    =================================== PICK A BUCKET ===================================
     */
    if (PerchUtil::count($buckets)) {

        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);

            $Listing->add_col([
                'title'     => 'Bucket',
                'value'     => function($Bucket) use ($HTML) {
                    return $HTML->encode(PerchUtil::filename($Bucket->get_name()));
                },
                'icon' => 'core/box-storage',
            ]);

            $Listing->add_col([
                'title'     => 'Type',
                'value'     => 'get_type',
            ]);

            $Listing->add_col([
                'title'     => 'Folder',
                'value'     => 'get_file_path',
            ]);

            $Listing->add_misc_action([
                'title'     => 'Find backups',
                'class'     => 'warning',
                'path'     => function($Bucket) use ($HTML) {
                    return PERCH_LOGINPATH.'/core/settings/backup/restore/general/?bucket='.$HTML->encode($Bucket->get_name());
                }
            ]);


        echo $Listing->render($buckets);

    }
    
    /*
    =================================== LIST FILES ===================================
     */
    if (PerchUtil::count($db_files)) {



        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);

            $Listing->add_col([
                'title'     => 'File',
                'value'     => 'id',
                'icon' => 'ext/database',
            ]);
            
            $Listing->add_col([
                'title'     => 'Date',
                'value'     => function($File) {
                    $file = $File->id();
                    $parts    = explode('_', $file);
                    $date_ext = array_pop($parts);
                    $parts    = explode('.', $date_ext);
                    $date_str = array_shift($parts);
                    return strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT, strtotime($date_str));
                },
            ]);


            $Listing->add_misc_action([
                'title'     => 'Restore',
                'class'     => 'warning',
                'path'     => function($File) use ($HTML, $Bucket) {
                    return PERCH_LOGINPATH.'/core/settings/backup/restore/general/?bucket='.$HTML->encode($Bucket->get_name()).'&amp;file='.$File->id();
                }
            ]);

        echo $Listing->render($Listing->objectify($db_files, 'file'));
        
    }

    echo $HTML->close('div');

    /*
    =================================== CONFIRM FORM ===================================
     */
    if ($confirm) {
        echo $Form->form_start();

        echo $HTML->warning_block('Restoring replaces your database', 'Restoring will revert the state of your database to the state it was in when this backup was made. Any content added since the backup was made will be lost. There is no undo. Are you sure?');

        echo $Form->submit_field('btnSubmit', 'Restore backup now');
        echo $Form->form_end();
    }

    