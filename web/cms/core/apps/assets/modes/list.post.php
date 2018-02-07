<?php

    echo $HTML->title_panel([
    'heading' => $Lang->get('Listing assets'),
    'button'  => [
                    'text' => $Lang->get('Add asset'),
                    'link' => '/core/apps/assets/edit/',
                    'icon' => 'core/plus',
                    'priv' => 'assets.create',
                ]
    ], $CurrentUser);

    include('_upload_progress.php');
    include('_smart_bar.php');

    echo $HTML->open('div.inner.asset-app-listing');

    if (PerchUtil::count($assets)) {
     

        if ($view == 'list') {
            include('_asset_list.php');
        }else{
            include('_asset_grid.php');
        }

    }

    echo $HTML->close('div');
