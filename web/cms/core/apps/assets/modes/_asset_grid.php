<?php
	
	echo $HTML->open('div.asset-grid');

	foreach($assets as $Asset) { 
		echo PerchAssets_Display::grid_item($Asset, $HTML);
	}

	echo $HTML->close('div');

    if ($Paging->enabled()) {
        echo '<div class="paging-cont">';
        echo $HTML->paging($Paging);
        echo '</div>';
    }