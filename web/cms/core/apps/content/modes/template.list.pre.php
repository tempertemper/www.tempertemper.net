<?php

	$API    = new PerchAPI(1.0, 'content');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(30);

    $Templates = new PerchContent_PageTemplates;

    $Templates->find_and_add_new_templates();

    $templates = $Templates->all($Paging);
    
