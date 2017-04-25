<?php
    # include the API
    include('../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'perch_forms');
    $Lang = $API->get('Lang');

    # Set the page title
    $Perch->page_title = $Lang->get('Form Settings');

    # Do anything you want to do before output is started
    include('../modes/_subnav.php');
    include('../modes/form.settings.pre.php');
    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    
    # Display your page
    include('../modes/form.settings.post.php');
    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
?>