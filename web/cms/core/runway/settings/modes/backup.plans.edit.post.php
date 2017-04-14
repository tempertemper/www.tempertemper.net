<?php 
    
    if ($Plan) {
        $heading = $Lang->get('Editing ‘%s’ backup plan', $HTML->encode($Plan->planTitle()));
    }else{
        $heading = $Lang->get('Adding a new backup plan');         
    }

    echo $HTML->title_panel([
        'heading' => $heading,
        ]); 
        
    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    // Set up a smartbar
    if ($Plan) {

        $Smartbar->add_item([
                'active'    => true,
                'type'  => 'breadcrumb',
                'links' => [
                    [
                        'link'  => '/core/settings/backup/',
                        'title' => 'Plans',
                    ],
                    [
                        'link'      => '/core/settings/backup/?id='.$Plan->id(),
                        'title'     => $Plan->planTitle(),
                        'translate' => false,
                    ]
                ],
            ]);

        $Smartbar->add_item([
                'link'  => '/core/settings/backup/edit/?id='.$Plan->id(),
                'title' => 'Plan Options',
                'icon'  => 'core/o-toggles',
            ]);

    }else{


        $Smartbar->add_item([
                'active'    => true,
                'type'  => 'breadcrumb',
                'links' => [
                    [
                        'link'  => '/core/settings/backup/',
                        'title' => 'Plans',
                    ],
                    [
                        'link'      => '/core/settings/backup/edit/',
                        'title'     => 'New Plan',
                    ]
                ],
            ]);
    }

    echo $Smartbar->render();


    

    // Output the edit form
    echo $Form->form_start();

    // Sub head
    echo $HTML->heading2('Details');

    $details = array();
    if (is_object($Plan)) $details = $Plan->to_array();
    
    echo $Form->fields_from_template($Template, $details, array(), false);

    echo $Form->submit_field();
    echo $Form->form_end();