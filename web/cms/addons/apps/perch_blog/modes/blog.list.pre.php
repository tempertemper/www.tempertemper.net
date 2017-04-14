<?php
	if (!PERCH_RUNWAY) exit;

    $Blogs = new PerchBlog_Blogs($API);

    $HTML = $API->get('HTML');

    if (!$CurrentUser->has_priv('perch_blog.blogs.manage')) {
        PerchUtil::redirect($API->app_path());
    }

    $blogs = $Blogs->all($Paging);
