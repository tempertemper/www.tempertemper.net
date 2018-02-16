<?php
  perch_layout('head');
  perch_layout('testimonials/header');
  echo '<main role="main">';
  perch_collection('Testimonials', [
    'template' => 'testimonial.html',
    'filter'   => 'slug',
    'match'    => 'eq',
    'value'    => perch_get('s'),
    'count'    => 1,
  ]);
  perch_layout('call_to_action');
  echo '</main>';
  perch_layout('footer');
  perch_layout('end');
