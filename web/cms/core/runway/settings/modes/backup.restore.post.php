<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Restoring a backup'),
    ], $CurrentUser);

echo $message;

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
            'active' => true,
            'type' => 'breadcrumb',
            'links' => [
                [   
                    'title' => 'Plans',
                    'link'  => '/core/settings/backup/',
                ],
                [
                    'title' => $Plan->planTitle(),
                    'translate' => false,
                    'link' => '/core/settings/backup/?id='.$Plan->id(),
                ],
                [
                    'title' => 'Restore',
                ]
            ],
            
        ]);

    $Smartbar->add_item([
            'title' => 'Plan Options',
            'link'  => '/core/settings/backup/edit/?id='.$Plan->id(),
            'icon' => 'core/o-toggles'
        ]);

    echo $Smartbar->render();



    // Output the edit form
    echo $Form->form_start();

    echo $HTML->warning_block('Restoring replaces your database', 'Restoring will revert the state of your database to the state it was in when this backup was made. Any content added since the backup was made will be lost. There is no undo. Are you sure?');

    echo $Form->submit_field('btnSubmit', 'Restore backup now', PERCH_LOGINPATH.'/core/settings/backup/?id='.$Plan->id());
    echo $Form->form_end();
    