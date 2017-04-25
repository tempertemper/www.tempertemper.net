<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Add-on versions'),
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
        'active'   => true,
        'title'    => 'Add-ons',
        'link'     => '/core/settings/diagnostics/add-ons/',
        'icon'     => 'blocks/pencil-paintbrush-pen',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'Update',
        'link'     => '/core/settings/update/',
        'icon'     => 'ext/o-sync',
        'position' => 'end',
    ]);
    echo $Smartbar->render();

    $apps_list = $Perch->get_apps();

    if (PerchUtil::count($apps_list)) {
        $messages = [];

        foreach($apps_list as $app) {
            if (isset($app_versions[$app['id']])) {
                if (version_compare($app['version'], $app_versions[$app['id']], '<')) {
                    $messages[] = [
                        'status' => 'warning',
                        'message' => PerchLang::get('An update is available for %s. You have %s and the current version is %s', $app['label'], $app['version'], $app_versions[$app['id']]),
                    ];
                } else {
                   $messages[] = [
                        'status' => 'success',
                        'message' => PerchLang::get('%s %s is up to date', $app['label'], $app['version']),
                    ];
                }

            }
        }

        if (PerchUtil::count($messages)) {
            $h = $HTML->wrap('h3', PerchLang::get('Add-on versions'));
            echo $HTML->wrap('div.inner', $h . PerchUI::render_progress_list($HTML, $messages));
        }

    }

    if (!PERCH_RUNWAY) {
        echo $HTML->info_block("Menu management", "If you have added and removed apps, you may wish to rebuild the sidebar menu to remove any orphaned menu items that belong to apps that are no longer present.");

        echo $HTML->wrap('span.inner a.button.button-simple.action-info[href=?menu=rebuild]', 'Rebuild now');
    }
