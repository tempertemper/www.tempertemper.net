<?php
  perch_layout('head');
  perch_layout('header');
  echo '<main role="main">';
  perch_collection('Testimonials', [
    'template'=>'testimonial_list.html',
    'paginate'=>'true',
    'count'=>10,
    'sort'=>'date',
    'sort-order'=>'DESC'
  ]);
  echo '</main>';
  perch_layout('footer');
  perch_layout('end');
