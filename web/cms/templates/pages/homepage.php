<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main">';
perch_content('Introduction');
perch_collection('Projects', [
    'template' => 'project_teaser.html',
    'count'=>2,
]);
echo '<aside class="testimonials">';
echo '<h1>Testimonials</h1>';
perch_collection('Testimonials', [
    'sort'=>'citation',
    'sort-order'=>'RAND',
    'count'=>1,
    'template'=>'testimonial_feature.html',
    'filter' => 'featured',
    'match' => 'eq',
    'value' => 'featured',
]);
echo '</aside>';
perch_layout('call_to_action');
echo '</main>';
perch_layout('footer');
perch_layout('end');
