<?php 
    include (PERCH_PATH.'/core/inc/sidebar_start.php');
 
    echo $HTML->para('Restore your database from backup.');
 
    include (PERCH_PATH.'/core/inc/sidebar_end.php'); 
    include (PERCH_PATH.'/core/inc/main_start.php'); 
    include ($app_path.'/modes/_subnav.php'); 
 

    echo $HTML->heading1('Restoring a Backup');         
        


    echo $HTML->smartbar(
        $HTML->smartbar_link(true, 
                array( 
                    'link'=> PERCH_LOGINPATH.'/core/settings/backup/?id='.$Plan->id(),
                    'label' => $Plan->planTitle(),
                )
        ),

        $HTML->smartbar_link(false, 
                array( 
                    'link'=> PERCH_LOGINPATH.'/core/settings/backup/edit/?id='.$Plan->id(),
                    'label' => PerchLang::get('Plan Options'),
                )
        )
    );



    // If a success or failure message has been set, output that here
    echo $message;


    // Output the edit form
    echo $Form->form_start();

    echo $HTML->warning_message('Restoring will revert your database to the state it was in when this backup was made. There is no undo. Are you sure?');

    echo $Form->submit_field('btnSubmit', 'Restore backup now');
    echo $Form->form_end();
    
    // Footer
    include (PERCH_PATH.'/core/inc/main_end.php');
