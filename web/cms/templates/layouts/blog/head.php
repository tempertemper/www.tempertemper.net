<!doctype html>
<!--[if lte IE 8 ]><html class="ie" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->
<head>
    <?php
        perch_layout('_ie_specific');

        $post = perch_blog_custom([
            'filter'        => 'postSlug',
            'match'         => 'eq',
            'value'         => perch_get('s'),
            'skip-template' => 'true',
            'return-html'   => 'true',
        ]);

        $title = $post['0']['postTitle'];
        if (isset($post['0']['excerpt']) && $post['0']['excerpt'] != '') {
            $description = strip_tags($post['0']['excerpt']);
        } else {
            $description = strip_tags($post['0']['postDescHTML']);
        }
        $type = 'article';

        perch_page_attributes_extend([
            'description' => $description,
            'title'       => $title,
            'type'        => $type,
        ]);

        perch_layout('_attributes');
        perch_layout('_mobile_specific');
        perch_layout('_apple_touch_icon');
        perch_layout('_favicon');
        perch_layout('_browser_styling');
        perch_get_css();
        perch_layout('_fonts');
        perch_layout('_google_sitename');
    ?>
    <link rel="alternate" type="application/rss+xml" title="RSS" href="/blog/rss" />
</head>
<body class="blog-post <?php perch_blog_post_field(perch_get('s'), 'postSlug'); ?>" class="hentry">
