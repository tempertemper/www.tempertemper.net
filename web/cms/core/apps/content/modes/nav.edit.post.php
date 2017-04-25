<?php 
        if (is_object($NavGroup)) {
            $heading = sprintf(PerchLang::get('Editing ‘%s’ Navigation Group'), PerchUtil::html($NavGroup->groupTitle())); 
        }else{
            $heading = sprintf(PerchLang::get('Creating New Navigation Group')); 
        }

        echo $HTML->title_panel([
            'heading' => $heading,
            ]);


        
        if ($groupID) {
            $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
            $Smartbar->add_item([
                'active' => false,
                'type'   => 'breadcrumb',
                'links'  => [
                    [
                        'title' => 'Navigation groups',
                        'link'  => '/core/apps/content/navigation/',
                    ],
                    [
                        'title' => $NavGroup->groupTitle(),
                        'translate' => false,
                        'link'  => '/core/apps/content/navigation/pages/?id='.$groupID,
                    ]
                ]
            ]);
            $Smartbar->add_item([
                'active'   => true,
                'title'    => 'Group Options',
                'link'     => '/core/apps/content/navigation/edit/?id='.$groupID,
                'icon'   => 'core/o-toggles',
            ]);
            $Smartbar->add_item([
                'active'   => false,
                'title'    => 'Reorder',
                'link'     => '/core/apps/content/navigation/reorder/?id='.$groupID,
                'icon'     => 'core/menu',
                'position' => 'end',
            ]);
            echo $Smartbar->render();
        }


    echo $HTML->heading2('Details');

    echo $Form->form_start();
?>
        <div class="field-wrap">
            <?php echo $Form->label('groupTitle', 'Title'); ?>
            <div class="form-entry">
            <?php echo $Form->text('groupTitle', $Form->get($details, 'groupTitle')); ?>
            </div>
        </div>        
<?php

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Submit', 'button'),
            ]);
    echo $Form->form_end();