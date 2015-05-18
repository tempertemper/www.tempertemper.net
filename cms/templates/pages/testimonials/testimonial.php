<?php perch_layout('start'); ?>
<?php perch_layout('header'); ?>

<div role="main">

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

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>