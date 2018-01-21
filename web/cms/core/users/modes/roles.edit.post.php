<?php 
    if (is_object($Role)) {
        $heading = $Lang->get('Editing ‘%s’ Role', PerchUtil::html($Role->roleTitle()));
    }else{
        $heading = $Lang->get('Adding a New Role');
    }

    echo $HTML->title_panel([
        'heading' => $heading,
        ]);


    if (is_object($Role)) {

        $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
            $Smartbar->add_item([
                'active' => true,
                'type'   => 'breadcrumb',
                'links'  => [
                    [
                        'title' => 'Roles',
                        'link'  => '/core/users/roles/',
                    ],
                    [
                        'title' => $Role->roleTitle(),
                        'translate' => false,
                        'link'  => '/core/users/roles/edit/?id='.$Role->id(),
                    ]
                ]
            ]);

            if (PERCH_RUNWAY) {
                $Smartbar->add_item([
                    'active'   => false,
                    'title'    => 'Buckets',
                    'link'     => '/core/users/roles/buckets/?id='.$Role->id(),
                    'icon'   => 'core/o-box-storage',
                ]);
            }


            $Smartbar->add_item([
                'active'   => false,
                'title'    => 'Actions',
                'link'     => '/core/users/roles/actions/?id='.$Role->id(),
                'icon'   => 'assets/o-clapper',
            ]);

        echo $Smartbar->render();

    }


    echo $Form->form_start();
    echo $HTML->heading2('Details');
?>
    <div class="field-wrap <?php echo $Form->error('roleTitle', false);?>">
        <?php echo $Form->label('roleTitle', 'Title'); ?>
        <div class="form-entry">
            <?php echo $Form->text('roleTitle', $Form->get($details, 'roleTitle')); ?>
        </div>
    </div>
<?php
    echo $HTML->heading2('Privileges');

    if (PerchUtil::count($privs)) {
        
        $previous = false;
        
        foreach($privs as $Priv) {
            if ($Priv->app() != $previous && $previous !==false) {
                
                if ($previous !== false) {
                    echo $Form->checkbox_set('privs-'.$previous, $Perch->app_name($previous), $opts, $existing_privs, false);
                }
                
                $opts = array();

                $API   = new PerchAPI(1, $Priv->app());
                $Lang  = $API->get('Lang');
                
            }

            if (is_object($Role)) {
                $disabled = $Role->roleMasterAdmin();
            }else{
                $disabled = false;
            }
            
            switch($Priv->app()) {

                case 'perch':
                case 'content':
                    $title = PerchLang::get($Priv->privTitle());
                    break;

                default:
                    $title = $Lang->get($Priv->privTitle());
                    break;

            }

            $opts[] = array('label'=>$title, 'value'=>$Priv->id(), 'disabled'=>$disabled);
            
            $previous = $Priv->app();
        }
        
        if (PerchUtil::count($opts)) {
            echo $Form->checkbox_set('privs-'.$previous, $Perch->app_name($previous), $opts, $existing_privs);
        }
        
        
    }
    
            
        
    echo $HTML->submit_bar([
            'button' =>  $Form->submit('submit', 'Save changes', 'button'),
            'cancel_link' => '/core/users/roles/',
        ]);

    echo $Form->form_end();
        