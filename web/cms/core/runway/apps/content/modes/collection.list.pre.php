<?php

	$Paging = new PerchPaging();
	$Paging->set_per_page(48);

	$API  = new PerchAPI(1.0, 'content');
	$HTML = $API->get('HTML');
	$Lang = $API->get('Lang');

	$collections = $Collections->all($Paging);

