<?php

    // test to see if image folder is writable
    $image_folder_writable = is_writable(PERCH_RESFILEPATH);

    $Form = new PerchForm('settings');
    
    $req = array();
    $req['headerColour']        = "Required";
    
    $Form->set_required($req);
    
    
    if ($Form->posted() && $Form->validate()) {
    	$postvars = array('headerColour', 'headerScheme', 'lang', 'hideBranding', 'helpURL', 'siteURL', 'dashboard', 'hide_pwd_reset');
    	$checkboxes = array('hideBranding', 'dashboard', 'hide_pwd_reset');
    	
    	include('_app_settings.pre.php');
    	
    	$data = $Form->receive($postvars);
    	
    	foreach($checkboxes as $checkbox) {
    	    if (!isset($data[$checkbox])) $data[$checkbox] = '0';
    	}
    	
        if (isset($_POST['logo_remove']) && $_POST['logo_remove']=='1') {
            $data['logoPath'] = '';
        }

    	foreach($data as $key=>$value) {
    	    $Settings->set($key, $value);
    	}
    	
    	
        
        $Lang = PerchLang::fetch();
        $Lang->reload();
    	
    	$Alert->set('success', PerchLang::get("Your settings have been updated."));
    	
    	// image upload
    	if (isset($_FILES['logo']) &&  (int) $_FILES['logo']['size'] > 0) {
    	    
    	    $filename = $_FILES['logo']['name'];
            if (strpos($filename, '.php')!==false) $filename .= '.txt'; // diffuse PHP files  
            $target = PERCH_RESFILEPATH.'/'.$filename;
            if (file_exists($target)) {
                $filename = time().'_'.$_FILES['logo']['name'];
                $target = PERCH_RESFILEPATH.'/'.$filename;
            }
            
            PerchUtil::move_uploaded_file($_FILES['logo']['tmp_name'], $target);
            
            $Settings->set('logoPath', PERCH_RESPATH . '/' . $filename);
    	}

        $Settings->reload();
    	
    }
    
    
         
    PerchUtil::debug('Image folder writable? ' . $image_folder_writable);
    
    
    
    $details = $Settings->get_as_array();
    
?>