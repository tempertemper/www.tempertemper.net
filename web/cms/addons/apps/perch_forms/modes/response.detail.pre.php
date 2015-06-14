<?php
   
    $Responses = new PerchForms_Responses($API);

    $HTML = $API->get('HTML');
	
	$message = false;
	
	if (isset($_GET['id']) && $_GET['id']!='') {
	    $Response = $Responses->find($_GET['id']);
	}else{
	    PerchUtil::redirect($API->app_path());
	}
    
    
    if (isset($_GET['file']) && $_GET['file']!='') {
        $files = $Response->files();
        $file = $files->$_GET['file'];
        
        if (file_exists($file->path)) {
            header('Content-Type: '.$file->mime);
            header('Content-Length: '.filesize($file->path));
            header('Content-Disposition: attachment;filename="'.$file->name.'"');
            $fp=fopen($file->path,'r');
            fpassthru($fp);
            fclose($fp);
            exit;
        }
    }
    
    
    $Form = $API->get('Form');
	if ($Form->submitted()) {
        if ($Response->responseSpam()) {
            $Response->mark_not_spam();
            $message = $HTML->success_message('Successfully marked as not being spam.');
        }else{
            $Response->mark_as_spam();
            $message = $HTML->success_message('Successfully marked as spam.');
        }
        
    }


?>