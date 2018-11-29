<header role="banner">
    <?php
        perch_layout('_logo');
        perch_layout('_nav_toggle');
        perch_layout('_primary_nav');
    ?>
    <h1 <?php
    if (perch_layout_var('page', true) == 'blog_post') {
        echo 'class="entry-title"';
    }
    ?> >
        <?php
            if (perch_layout_var('page', true) == 'resource_category') {
                echo 'Resource category: ';
                perch_category('resources/'.perch_get('cat').'/', [
                    // 'template' => 'category_header.html',
                ]);
            } elseif (perch_layout_var('page', true) == 'resource') {
                $resource = perch_collection('Resources', [
                    'filter'        => 'slug',
                    'match'         => 'eq',
                    'value'         => perch_get('s'),
                    'skip-template' => 'true',
                    'return-html'   => 'true',
                ]);
                $title = $resource['0']['title'];
                echo $title;
            } elseif (perch_layout_var('page', true) == 'testimonial') {
                perch_collection('Testimonials', [
                    'template' => 'testimonial_header.html',
                    'filter'   => 'slug',
                    'match'    => 'eq',
                    'value'    => perch_get('s'),
                    'count'    => 1,
                ]);
            } elseif (perch_layout_var('page', true) == 'service') {
                perch_collection('Services', [
                    'filter' => 'slug',
                    'match' => 'eq',
                    'value' => perch_get('s'),
                    'count' => 1,
                    'template'=>'/project/page_header.html',
                ]);
            } elseif (perch_layout_var('page', true) == 'project') {
                perch_collection('Projects', [
                    'template'=>'/project/page_header.html',
                    'filter'   => [
                        [
                            'filter'   => 'slug',
                            'match'    => 'eq',
                            'value'    => perch_get('s'),
                            'count'    => 1,
                        ],
                        [
                            'filter'   => 'published',
                            'match'    => 'eq',
                            'value'    => 'true',
                        ],
                    ],
                ]);
            } elseif (perch_layout_var('page', true) == 'blog_post') {
                perch_blog_post_field(perch_get('s'), 'postTitle');
            } else {
                perch_content('Headline');
            };
        ?>
    </h1>
</header>
