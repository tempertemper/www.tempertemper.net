<!doctype html>
<!--[if lte IE 8 ]><html class="ie8 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if IE 9 ]><html class="ie9 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8" />
    <?php
        if (perch_layout_var('page', true) == 'resource') {
            $collection = 'Resources';
        };
        if (perch_layout_var('page', true) == 'service') {
            $collection = 'Services';
        };
        if (perch_layout_var('page', true) == 'project') {
            $collection = 'Projects';
        };
        if (perch_layout_var('page', true) == 'blog_post') {
            $items = perch_blog_custom([
                'filter'        => 'postSlug',
                'match'         => 'eq',
                'value'         => perch_get('s'),
                'skip-template' => 'true',
                'return-html'   => 'true',
            ]);
            $title = $items['0']['postTitle'];
            if (isset($items['0']['excerpt']) && $items['0']['excerpt'] != '') {
                $description = strip_tags($items['0']['excerpt']);
            } else {
                $description = strip_tags($items['0']['postDescHTML']);
            }
        };

        if (perch_layout_var('page', true) == 'resource' || perch_layout_var('page', true) == 'service' || perch_layout_var('page', true) == 'project') {
            $items = perch_collection($collection, [
                'filter'        => 'slug',
                'match'         => 'eq',
                'value'         => perch_get('s'),
                'skip-template' => 'true',
                'return-html'   => 'true',
            ]);
            $title = $items['0']['title'];
        };

        if (perch_layout_var('page', true) == 'resource') {
            if (isset($items['0']['excerpt']) && $items['0']['excerpt'] != '') {
                $description = strip_tags($items['0']['excerpt']);
            } else {
                $description = strip_tags($items['0']['resource']);
            }
        };

        if (perch_layout_var('page', true) == 'resource' || perch_layout_var('page', true) == 'blog_post') {
            $type = 'article';
            perch_page_attributes_extend([
                'description' => $description,
                'title'       => $title,
                'type'        => $type,
            ]);
        };

        if (perch_layout_var('page', true) == 'service' || perch_layout_var('page', true) == 'project') {
            if (isset($items['0']['excerpt']) && $items['0']['excerpt'] != '') {
                $description = strip_tags($items['0']['excerpt']);
            } else {
                $description = strip_tags($items['0']['description']);
            }
            perch_page_attributes_extend([
                'description' => $description,
                'title'       => $title,
            ]);
        };

        perch_layout('_attributes');
        perch_layout('_mobile_specific');
        perch_layout('_apple_touch_icon');
        perch_layout('_favicon');
        perch_layout('_browser_styling');
        perch_layout('_javascript');
        perch_get_css();
        perch_layout('_google_sitename');
    ?>
    <link rel="alternate" type="application/rss+xml" title="RSS" href="/blog/rss" />
</head>
<body class="<?php echo PerchUtil::urlify(perch_pages_navigation_text(true));?>">
    <div class="canvas">
        <div class="page-wrapper">
            <a href="#main" tab-index="0" class="skip-nav visually-hidden">Skip to main content</a>
