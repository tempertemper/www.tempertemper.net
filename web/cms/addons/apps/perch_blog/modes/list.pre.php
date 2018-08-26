<?php
    // Try to update
    $Settings = $API->get('Settings');

    if ($Settings->get('perch_blog_update')->val()!='5.6') {
        PerchUtil::redirect($API->app_path().'/update/');
    }

    $Posts = new PerchBlog_Posts($API);
    $Blogs = new PerchBlog_Blogs($API);
    $blogs = $Blogs->all();

    if (!PerchUtil::count($blogs)) {
         $Posts->attempt_install();
         $blogs = $Blogs->all();
    }

    $Paging->set_per_page(15);

    $Blog = false;

    if (PERCH_RUNWAY) {
        if (PerchUtil::get('blog')) {
            $Blog = $Blogs->get_one_by('blogSlug', PerchUtil::get('blog'));
        }
    }
    if (!$Blog) {
        $Blog = $Blogs->first();

        if (!$Blog) {
            PerchUtil::redirect($API->app_path().'/update/?force=update');
        }
    }


    $Categories = new PerchCategories_Categories();
    $categories = $Categories->get_for_set($Blog->setSlug());

    $Sections = new PerchBlog_Sections($API);
    $sections = $Sections->get_by('blogID', (int)$Blog->id());


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
            $posts = $Posts->get_by_category_slug_for_admin_listing($category, $Paging);
            break;

        case 'section':
            $posts = $Posts->get_by_section_slug_for_admin_listing($section, $Paging);
            break;

        case 'status':
            $posts = $Posts->get_by_status($status, false, $Blog->blogSlug(), $Paging);
            break;

        default:
            $posts = $Posts->get_by('blogID', (int)$Blog->id(), 'postDateTime DESC', $Paging);

            // Install
            if ($posts == false) {
                $Posts->attempt_install();
            }

            break;
    }
