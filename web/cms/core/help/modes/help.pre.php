<?php
	$API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    if ($CurrentUser->userMasterAdmin()) {
      	$Alert->set('info' , 'For help configuring Perch and writing templates, visit the <a href="http://docs.grabaperch.com/">online documentation</a>.');
    }
