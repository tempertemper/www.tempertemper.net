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


    include('_smart_bar.php');

    echo $HTML->open('div.inner.asset-app-listing');

    if (PerchUtil::count($assets)) {
     

        if ($view == 'list') {
            include('_asset_list.php');
        }else{
            include('_asset_grid.php');
        }

        if ($Paging->enabled()) {
            echo '<div class="paging-cont">';
            echo $HTML->paging($Paging);
            echo '</div>';
        }

    }

    echo $HTML->close('div');
