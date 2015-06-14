<?php
  perch_layout('head');
  perch_layout('header');
?>

<main role="main">

  <?php
    perch_collection('Testimonials', array(
      'template'=>'testimonial.html',
      'filter'   => 'slug',
      'match'    => 'eq',
      'value'    => perch_get('s'),
      'count'    => 1,
    ));
  ?>

  <a href="/testimonials" class="back">View all testimonials</a>

  <?php perch_content('Call to action'); ?>

</main>

<?php
  perch_layout('footer');
  perch_layout('end');
?>