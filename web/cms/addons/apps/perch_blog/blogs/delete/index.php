<?php
    # include the API
    include(__DIR__.'/../../../../../core/inc/api.php');

    if (!PERCH_RUNWAY) {
        exit;
    }

    $API  = new PerchAPI(1.0, 'perch_blog');
    $HTML   = $API->get('HTML');
    $Lang   = $API->get('Lang');
    $Paging = $API->get('Paging');

    # Set the page title
    $Perch->page_title = $Lang->get('Manage Blogs');

    $Perch->add_css($API->app_path().'/assets/css/blog.css');

    # Do anything you want to do before output is started
    include(__DIR__.'/../../modes/_subnav.php');
    include(__DIR__.'/../../modes/blog.delete.pre.php');

    # Top layout
    include(PERCH_CORE . '/inc/top.php');

    # Display your page
    include(__DIR__.'/../../modes/blog.delete.post.php');


    # Bottom layout
    include(PERCH_CORE . '/inc/btm.php');
