<?php  // /core/update/?force=update

    echo $HTML->title_panel([
        'heading' => $Lang->get('Software update'),
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Basic',
        'link'     => '/core/settings/diagnostics/',
        'icon'     => 'core/info-alt',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Extended',
        'link'     => '/core/settings/diagnostics/?extended',
        'icon'     => 'core/gear',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Add-ons',
        'link'     => '/core/settings/diagnostics/add-ons/',
        'icon'     => 'blocks/pencil-paintbrush-pen',
    ]);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Update',
        'link'     => '/core/settings/update/',
        'icon'     => 'ext/o-sync',
        'position' => 'end',
    ]);
    echo $Smartbar->render();


    echo $HTML->info_block("Manual update", "Software updates run automatically when the system is updated. If you're experiencing problems or suspect that the update did not run as it should have, you can run it manually.");

    echo $HTML->wrap('div.inner a.button.button-simple.action-info[href='.PERCH_LOGINPATH.'/core/update/?force=update]', 'Update now');