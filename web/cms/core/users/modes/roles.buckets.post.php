<?php 
    echo $HTML->title_panel([
        'heading' => $Lang->get('Editing ‘%s’ Role', PerchUtil::html($Role->roleTitle())),
    ]);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
                'active' => false,
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
                    'active'   => true,
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


    echo $Form->form_start();
    echo $HTML->heading2('Asset Buckets');

    if (PerchUtil::count($buckets)) {

        $existing_privs = $Role->get_bucket_privs_for_edit();
        $opts_set = [];
        
        foreach($buckets as $Bucket) {

            $disabled = false;

            if ($Bucket->get_role() == 'backup') {
                $disabled = true;
            }

            $opts = [];
            $opts[] = ['label'=>$Lang->get('Select from'), 'value'=>'select', 'disabled'=>$disabled];
            $opts[] = ['label'=>$Lang->get('Upload to'), 'value'=>'insert', 'disabled'=>$disabled];
            $opts[] = ['label'=>$Lang->get('Delete from'), 'value'=>'delete', 'disabled'=>$disabled];
            $opts[] = ['label'=>$Lang->get('Make default'), 'value'=>'default', 'disabled'=>$disabled];

            $opts_set[] = [
                'id'       => 'privs-'.$Bucket->get_name(),
                'value_id' => $Bucket->get_name(),
                'label'    => $Bucket->get_label(),
                'opts'     => $opts,
            ];

        }

        echo $Form->checkbox_table($opts_set, $existing_privs);

    }

    echo $HTML->submit_bar([
        'button' =>  $Form->submit('submit', 'Save changes', 'button'),
        'cancel_link' => '/core/users/roles/',
    ]);

    echo $Form->form_end();
        