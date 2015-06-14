<?php
  perch_collection('Testimonials', array(
    'sort'=>'citation',
    'sort-order'=>'RAND',
    'count'=>1,
    'template'=>'testimonial_feed.html'
  ));
?>