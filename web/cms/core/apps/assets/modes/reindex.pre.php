<?php

	$API  = new PerchAPI(1.0, 'assets');
	$HTML = $API->get('HTML');
	$Lang = $API->get('Lang');

	
	$Assets = new PerchAssets_Assets;
	$Assets->reindex();