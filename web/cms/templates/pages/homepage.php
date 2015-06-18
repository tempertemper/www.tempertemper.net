<?php
  perch_layout('head');
  perch_layout('header');
?>

<main role="main">

  <?php perch_content('Introduction'); ?>

  <?php
    perch_collection('Work', [
      'template' => 'work_teaser.html',
      'count'=>3,
      'sort'=>'date',
      'sort-order'=>'DESC'
    ]);
  ?>

  <aside class="testimonials">

    <h1>Testimonials</h1>

    <?php perch_layout('testimonial'); ?>

  </aside>

  <?php perch_content('Call to action'); ?>

</main>

<?php
  perch_layout('footer');
  perch_layout('end');
?>