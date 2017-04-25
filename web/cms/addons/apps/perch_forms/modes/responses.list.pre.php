<?php
    
    $Forms = new PerchForms_Forms($API);
    $Responses = new PerchForms_Responses($API);

    $filter = 'all';

    if (isset($_GET['id']) && $_GET['id']!='') {
	    $Form = $Forms->find($_GET['id']);
	    
	    $spam = false;
	    if (isset($_GET['spam']) && $_GET['spam']=1) {
	        $spam = true;
	        $filter = 'spam';
	    }
	    
	    $responses = $Responses->get_for_from($_GET['id'], $Paging, $spam);
	}else{
	    PerchUtil::redirect($API->app_path());
	}
	