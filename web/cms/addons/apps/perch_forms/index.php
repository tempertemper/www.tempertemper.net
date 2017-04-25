<?php
    # include the API
    include('../../../core/inc/api.php');
    
    $API  = new PerchAPI(1.0, 'perch_forms');
    $HTML   = $API->get('HTML');
    $Lang   = $API->get('Lang');
    $Paging = $API->get('Paging');
    


    # Set the page title
    $Perch->page_title = $Lang->get('Forms');

    include('modes/_subnav.php');
    include('modes/forms.list.pre.php');
    
    
    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    
    # Display your page
    include('modes/forms.list.post.php');
    
    
    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
