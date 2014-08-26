<?php
  $opts = array(
    'page'=>'/testimonials/index.php',
    'sort'=>'citation',
    'sort-order'=>'RAND',
    'count'=>1,
    'template'=>'testimonial_feed.html'
  );
  perch_content_custom('Primary content', $opts);
?>