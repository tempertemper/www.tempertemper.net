<?php
    
    $Forms = new PerchForms_Forms($API);
    $Responses = new PerchForms_Responses($API);

    $HTML = $API->get('HTML');
    
    $Paging = $API->get('Paging');
    $Paging->disable();

    if (isset($_GET['id']) && $_GET['id']!='') {
	    $Form = $Forms->find($_GET['id']);
	    $responses = $Responses->get_for_from($_GET['id'], $Paging);
	}else{
	    PerchUtil::redirect($API->app_path());
	}
	
	//header('Content-type: text/plain', true);
	header('Content-type: text/csv', true);
	header("Content-Disposition: attachment; filename=\"".$Form->formKey().'-'.date('Y-m-d-H:i').".csv\"", true);
	
	if (PerchUtil::count($responses)) {
	    
	    $headers = array();
	    $rows = array();
	    
	    
	    
	    foreach($responses as $Response) {
            $row = array();
        
            // date
            $key = $Lang->get('date');
            if (!in_array($key, $headers)) $headers[] = $key;
            $pos = array_search($key, $headers);
            $row[$pos] = '"'.str_replace('"', '\"', $Response->responseCreated()).'"';
            
            // ip address
            $key = $Lang->get('ip_address');
            if (!in_array($key, $headers)) $headers[] = $key;
            $pos = array_search($key, $headers);
            $row[$pos] = '"'.str_replace('"', '\"', $Response->responseIp()).'"';
            
            // fields
            foreach($Response->fields() as $key=>$field) {
                if (!in_array($key, $headers)) $headers[] = $key;
                $pos = array_search($key, $headers);
                $row[$pos] = '"'.str_replace('"', '\"', $field->value).'"';
            }
            
            // files
            foreach($Response->files() as $key=>$file) {
                if (!in_array($key, $headers)) $headers[] = $key;
                $pos = array_search($key, $headers);
                $row[$pos] = '"'.str_replace('"', '\"', $file->name).'"';
            }
	        $rows[] = $row;
	    }
	    
	    echo implode(',', $headers) ."\n";
	    $hCount = PerchUtil::count($headers);
	    foreach($rows as $row) {
            for($i=0; $i<$hCount; $i++) {
                if (isset($row[$i])) echo $row[$i];
                echo ',';
            }
            echo "\n";
	    }
	    
	}
	
	exit;
    

?>