<?php
    # include the API
    include('../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'perch_backup');
    
    # Grab an instance of the Lang class for translations
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Backup data and customizations');


    # Do anything you want to do before output is started
    include('modes/index.pre.php');
    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');
    
    # Check that we are writable - if not display the error page, otherwise continue and display the backup page.
    if(!$CurrentUser->has_priv('perch_backup')){
    	include('modes/noaccess.post.php');
    
    }elseif($Backup->can_write_temp_file('backup')) {
    	include('modes/index.post.php');
    	#check to see if we can run this
    	
    
    }else{
    	include('modes/error.post.php');
    }
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
?>