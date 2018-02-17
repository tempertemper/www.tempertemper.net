<!doctype html>
<!--[if lte IE 8 ]><html class="ie8 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if IE 9 ]><html class="ie9 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->
<head>
    <?php
        perch_layout('_ie_specific');

        if (perch_layout_var('page', true) == 'resource') {

            $resource = perch_collection('Resources', [
                'filter'        => 'slug',
                'match'         => 'eq',
                'value'         => perch_get('s'),
                'skip-template' => 'true',
                'return-html'   => 'true',
            ]);

            $title = $resource['0']['title'];
            if (isset($resource['0']['excerpt']) && $resource['0']['excerpt'] != '') {
                $description = strip_tags($resource['0']['excerpt']);
            } else {
                $description = strip_tags($resource['0']['resource']);
            }
            $type = 'article';

            perch_page_attributes_extend([
                'description' => $description,
                'title'       => $title,
                'type'        => $type,
            ]);
        };

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
<body class="<?php echo PerchUtil::urlify(perch_pages_navigation_text(true));?>">
