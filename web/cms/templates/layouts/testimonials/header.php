<header role="banner">
    <?php
        perch_layout('_logo');
        perch_layout('_nav_toggle');
        perch_layout('_primary_nav');
    ?>
    <h1>
        <?php
            perch_collection('Testimonials', [
                'template' => 'testimonial_header.html',
                'filter'   => 'slug',
                'match'    => 'eq',
                'value'    => perch_get('s'),
                'count'    => 1,
            ]);
        ?>
    </h1>
</header>
