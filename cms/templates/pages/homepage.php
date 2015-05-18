<?php perch_layout('start'); ?>
<?php perch_layout('header'); ?>

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

</main>

<div role="complementary">

  <aside class="testimonials">

    <h1>Compliments</h1>

    <?php perch_layout('testimonial'); ?>

  </aside>

  <?php perch_content('Subscribe'); ?>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>