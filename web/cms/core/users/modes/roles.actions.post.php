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
                    'active'   => false,
                    'title'    => 'Buckets',
                    'link'     => '/core/users/roles/buckets/?id='.$Role->id(),
                    'icon'   => 'core/o-box-storage',
                ]);
            }
            
    $Smartbar->add_item([
            'active'   => true,
            'title'    => 'Actions',
            'link'     => '/core/users/roles/actions/?id='.$Role->id(),
            'icon'   => 'assets/o-clapper',
        ]);
    echo $Smartbar->render();


?>    
    <form action="<?php echo PerchUtil::html($Form->action(), true); ?>" method="post" class="form-simple">

        <div class="instructions">
            <p><?php echo PerchLang::get('These actions perform a one-time bulk edit to the site as it currently stands. They have no ongoing effect.'); ?></p>
        </div>


        <h2 class="divider"><div><?php echo PerchLang::get('Editing Regions'); ?></div></h2>

        <div class="instructions">
            <p><?php echo PerchLang::get('Modify all existing regions to grant or revoke permission for this role to edit.'); ?></p>
        </div>

        <fieldset class="fieldset-clean">
            <div class="fieldset-inner">

                <div class="legend-wrap">
                    <legend><?php echo PerchLang::get('Regions'); ?></legend>
                </div>

                <div class="checkbox-group">

                    <div class="checkbox-single">
                        <?php echo $Form->label('regions-noaction', 'Make no changes'); ?>
                        <div class="form-entry">
                            <?php echo $Form->radio('regions-noaction', 'regions', 'noaction', true); ?>
                        </div>
                    </div>

                    <div class="checkbox-single">
                        <?php echo $Form->label('regions-grant', 'Grant role permission to edit all regions'); ?>
                        <div class="form-entry">
                            <?php echo $Form->radio('regions-grant', 'regions', 'grant', false); ?>
                        </div>
                    </div>

                    <div class="checkbox-single">
                        <?php echo $Form->label('regions-revoke', 'Revoke role permissions to edit all regions'); ?>
                        <div class="form-entry">
                            <?php echo $Form->radio('regions-revoke', 'regions', 'revoke', false); ?>
                        </div>
                    </div>
                </div>

            </div>

        </fieldset>

        <h2 class="divider"><div><?php echo PerchLang::get('Creating Subpages'); ?></div></h2>

        <div class="instructions">
                <p><?php echo PerchLang::get('Modify all existing pages to grant or revoke permission for this role to be able to create subpages.'); ?></p>
        </div>

        <fieldset class="fieldset-clean">
            <div class="fieldset-inner">

                <div class="legend-wrap">
                    <legend><?php echo PerchLang::get('Pages'); ?></legend>
                </div>

                <div class="checkbox-group">

                    <div class="checkbox-single">
                        <?php echo $Form->label('pages-noaction', 'Make no changes'); ?>
                        <div class="form-entry">
                            <?php echo $Form->radio('pages-noaction', 'pages', 'noaction', true); ?>
                        </div>
                    </div>

                    <div class="checkbox-single">
                        <?php echo $Form->label('pages-grant', 'Grant role permission create new subpages of all current pages'); ?>
                        <div class="form-entry">
                            <?php echo $Form->radio('pages-grant', 'pages', 'grant', false); ?>
                        </div>
                    </div>

                    <div class="checkbox-single">
                        <?php echo $Form->label('pages-revoke', 'Revoke role permission to create new subpages'); ?>
                        <div class="form-entry">
                            <?php echo $Form->radio('pages-revoke', 'pages', 'revoke', false); ?>
                        </div>
                    </div>
                </div>

            </div>

        </fieldset>
<?php

        echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Make changes', 'button'),
                'cancel_link' => '/core/users/roles/'
            ]);
?>

    </form>
