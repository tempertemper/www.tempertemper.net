<?php
    
    if ($Set) {
        $heading = $Lang->get('Editing ‘%s’ Category Set', $HTML->encode($Set->setTitle()));
    }else{
        $heading = $Lang->get('Adding a New Category Set');         
    }

    echo $HTML->title_panel([
    'heading' => $heading
    ], $CurrentUser);
        
    // If a success or failure message has been set, output that here
    echo $message;

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    if ($Set) {

         $Smartbar->add_item([
            'active' => false,
            'type' => 'breadcrumb',
            'links' => [
                [
                    'title' => 'Sets',
                    'link'  => '/core/apps/categories/',
                ],
                [
                    'title' => $Set->setTitle(),
                    'link'  => '/core/apps/categories/sets/?id='.$Set->id(),
                    'translate' => false,
                ]
            ],
        ]);

        $Smartbar->add_item([
                'active' => true,
                'title'  => 'Set Options',
                'link'   => '/core/apps/categories/sets/edit?id='.$Set->id(),
                'icon'   => 'core/o-toggles',
            ]);

        $Smartbar->add_item([
                'active'   => false,
                'title'    => 'Reorder',
                'link'     => '/core/apps/categories/reorder/?id='.$Set->id(),
                'position' => 'end',
                'icon'     => 'core/menu',
            ]);


    } else {

        $Smartbar->add_item([
            'active' => true,
            'type' => 'breadcrumb',
            'links' => [
                [
                    'title' => 'New Set',
                    'link'  => '/core/apps/categories/sets/',
                ]
            ],
        ]);

    }

    echo $Smartbar->render();



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
    