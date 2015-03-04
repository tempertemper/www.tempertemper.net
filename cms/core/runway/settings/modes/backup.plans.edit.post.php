<?php 
    include (PERCH_PATH.'/core/inc/sidebar_start.php');
 
    echo $HTML->para('Update your backup plan.');
 
    include (PERCH_PATH.'/core/inc/sidebar_end.php'); 
    include (PERCH_PATH.'/core/inc/main_start.php'); 
    include ($app_path.'/modes/_subnav.php'); 
 
    
    if ($Plan) {
        echo $HTML->heading1('Editing ‘%s’ Backup Plan', $Plan->planTitle());
    }else{
        echo $HTML->heading1('Adding a New Backup Plan');         
    }
        

    // Set up a smartbar
    if ($Plan) {

        echo $HTML->smartbar(
            $HTML->smartbar_link(false, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/settings/backup/?id='.$Plan->id(),
                        'label' => $Plan->planTitle(),
                    )
            ),

            $HTML->smartbar_link(true, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/settings/backup/edit/?id='.$Plan->id(),
                        'label' => PerchLang::get('Plan Options'),
                    )
            )
        );


    }else{

        echo $HTML->smartbar(
                $HTML->smartbar_breadcrumb(true, 
                        array( 
                            'link'=> PERCH_LOGINPATH.'/core/apps/categories/sets/',
                            'label' => PerchLang::get('New Plan'),
                        )
                )
            );

    }


    // If a success or failure message has been set, output that here
    echo $message;

    // Sub head
    echo $HTML->heading2('Details');

    // Output the edit form
    echo $Form->form_start();

    $details = array();
    if (is_object($Plan)) $details = $Plan->to_array();
    
    echo $Form->fields_from_template($Template, $details, array(), false);

    echo $Form->submit_field();
    echo $Form->form_end();
    
    // Footer
    include (PERCH_PATH.'/core/inc/main_end.php');
