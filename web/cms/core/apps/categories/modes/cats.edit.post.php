<?php 
    // Side bar
    include (PERCH_PATH.'/core/inc/sidebar_start.php');

    // Help text for side bar
    echo $HTML->para('Update your category.');
 
    // Ends of sidebar and start of main col
    include (PERCH_PATH.'/core/inc/sidebar_end.php'); 
    include (PERCH_PATH.'/core/inc/main_start.php'); 

    // Include the subnav
    include ('_subnav.php'); 
 
    // Main heading - different if new vs edit mode
    if ($Category) {
        echo $HTML->heading1('Editing ‘%s’ Category', $HTML->encode($Category->catTitle()));
    }else{
        echo $HTML->heading1('Adding a New Category');         
    }
        

    // Set up a smartbar
    echo $HTML->smartbar(
            $HTML->smartbar_breadcrumb(true, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/apps/categories/sets/?id='.$Set->id(),
                        'label' => $Set->setTitle(),
                    ),
                    ( $Category ? 
                        array( 
                            'link'=> PERCH_LOGINPATH.'/core/apps/categories/edit/?id='.$Category->id(),
                            'label' => $Category->catTitle(),
                        ) :
                        array( 
                            'link'=> PERCH_LOGINPATH.'/core/apps/categories/edit/',
                            'label' => PerchLang::get('New Category'),
                        )
                    )
            ),
            $HTML->smartbar_link(false, 
                    array( 
                        'link'=> PERCH_LOGINPATH.'/core/apps/categories/sets/edit?id='.$Set->id(),
                        'label' => PerchLang::get('Set Options'),
                    )
                )
        );

    // If a success or failure message has been set, output that here
    echo $message;

    // Sub head
    echo $HTML->heading2('Details');

    // Output the edit form
    $Form->add_another = true;
    echo $Form->form_complete($Template, $Category);
    
    // Footer
    include (PERCH_PATH.'/core/inc/main_end.php');
