<?php
$page = 'testimonial';
perch_layout('head', [
    'page' => $page,
]);
perch_layout('header', [
    'page' => $page,
]);
echo '<main role="main" id="main">';
perch_collection('Testimonials', [
    'template' => 'testimonial.html',
    'filter'   => 'slug',
    'match'    => 'eq',
    'value'    => perch_get('s'),
    'count'    => 1,
]);
perch_content('Call to action');
echo '</main>';
perch_layout('footer');
perch_layout('end');
