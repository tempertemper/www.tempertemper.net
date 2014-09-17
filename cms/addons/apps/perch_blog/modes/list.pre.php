<?php
    
    $HTML = $API->get('HTML');
    
    // Try to update
    $Settings = $API->get('Settings');

    if ($Settings->get('perch_blog_update')->val()!='5.0') {
        PerchUtil::redirect($API->app_path().'/update/');
    }

        
    $Blog = new PerchBlog_Posts($API);
    
    $Paging = $API->get('Paging');
    $Paging->set_per_page(15);
    
    $Categories   = new PerchCategories_Categories();
    $categories = $Categories->get_for_set('blog');

    $Sections = new PerchBlog_Sections($API);
    $sections = $Sections->all();

	
   
	$Lang = $API->get('Lang');

    $posts = array();
	
    $filter = 'all';
    

    if (isset($_GET['category']) && $_GET['category'] != '') {
        $filter = 'category';
        $category = $_GET['category'];
    }

    if (isset($_GET['section']) && $_GET['section'] != '') {
        $filter = 'section';
        $section = $_GET['section'];
    }
    

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $filter = 'status';
        $status = $_GET['status'];
    }

    
    switch ($filter) {
        
        case 'category':
            $posts = $Blog->get_by_category_slug_for_admin_listing($category, $Paging);
            break;        

        case 'section':
            $posts = $Blog->get_by_section_slug_for_admin_listing($section, $Paging);
            break;

        case 'status':
            $posts = $Blog->get_by_status($status, false, $Paging);
            break;

        default:
            $posts = $Blog->all($Paging);
            
            // Install
            if ($posts == false) {
                $Blog->attempt_install();
            }
            
            break;
    }

?>