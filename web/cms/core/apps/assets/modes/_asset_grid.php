<?php
	
	echo $HTML->open('div.asset-grid');

	foreach($assets as $Asset) { 
		echo PerchAssets_Display::grid_item($Asset, $HTML);
	}

	echo $HTML->close('div');
