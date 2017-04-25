<?php
    # include the API
    include('../../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'perch_blog');
    $HTML   = $API->get('HTML');
    $Lang   = $API->get('Lang');
    $Paging = $API->get('Paging');

    # Set the page title
    $Perch->page_title = $Lang->get('Update Blog');


    # Do anything you want to do before output is started
    include('../modes/_subnav.php');
    include('../modes/update.pre.php');
    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    
    # Display your page
    include('../modes/update.post.php');
    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
