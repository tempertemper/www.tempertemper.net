<?php
	$API    = new PerchAPI(1.0, 'assets');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(24);

    $Tags = new PerchAssets_Tags();

    $tags = $Tags->all($Paging);
   