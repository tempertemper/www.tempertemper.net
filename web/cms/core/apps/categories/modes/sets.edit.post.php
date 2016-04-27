<?php 
    include (PERCH_PATH.'/core/inc/sidebar_start.php');
 
    echo $HTML->para('Update your category set.');
 
    include (PERCH_PATH.'/core/inc/sidebar_end.php'); 
    include (PERCH_PATH.'/core/inc/main_start.php'); 
    include ('_subnav.php'); 
 
    
    if ($Set) {
        echo $HTML->heading1('Editing ‘%s’ Category Set', $HTML->encode($Set->setTitle()));
    }else{
        echo $HTML->heading1('Adding a New Category Set');         
    }
        

    // Set up a smartbar
    if ($Set) {

        echo $HTML->smartbar(
                $HTML->smartbar_breadcrumb(false, 
                        array( 
                            'link'=> PERCH_LOGINPATH.'/core/apps/categories/sets/?id='.$Set->id(),
                            'label' => $Set->setTitle(),
                        )
                ),
                $HTML->smartbar_link(true, 
                        array( 
                            'link'=> PERCH_LOGINPATH.'/core/apps/categories/sets/edit?id='.$Set->id(),
                            'label' => PerchLang::get('Set Options'),
                        )
                    ),
                $HTML->smartbar_link(false, 
                        array( 
                            'link'=> PERCH_LOGINPATH.'/core/apps/categories/reorder/?id='.$Set->id(),
                            'label' => PerchLang::get('Reorder Categories'),
                            'class' => 'icon reorder'
                        ), 
                        true
                    )
            );

    }else{

        echo $HTML->smartbar(
                $HTML->smartbar_breadcrumb(true, 
                        array( 
                            'link'=> PERCH_LOGINPATH.'/core/apps/categories/sets/',
                            'label' => PerchLang::get('New Set'),
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
    if (is_object($Set)) $details = $Set->to_array();
    
    echo $Form->fields_from_template($Template, $details, array(), false);

    $opts = array();
    $templates = $Sets->get_templates();

    if (PerchUtil::count($templates)) {
        $opts = array();
        foreach($templates as $group_name=>$group) {
            $tmp = array();
            $group = PerchUtil::array_sort($group, 'label');
            foreach($group as $file) {
                $tmp[] = array('label'=>$file['label'], 'value'=>$file['path']);
            }
            $opts[$group_name] = $tmp;
        }
    }

    echo $Form->grouped_select_field('setTemplate', 'Set template', $opts, (isset($details['setTemplate'])? $details['setTemplate'] : 'set.html'));
    echo $Form->grouped_select_field('setCatTemplate', 'Category template', $opts, (isset($details['setCatTemplate'])? $details['setCatTemplate'] : 'category.html'));

    echo $Form->submit_field();
    echo $Form->form_end();
    
    // Footer
    include (PERCH_PATH.'/core/inc/main_end.php');
