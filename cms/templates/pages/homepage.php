<?php perch_layout('start'); ?>
<?php perch_layout('header'); ?>

<main role="main">

  <?php perch_content('Introduction'); ?>

  <?php
    perch_collection('Work', [
      'page'=>'/portfolio',
      'template' => 'portfolio_teaser.html',
      'count'=>2
    ]);
  ?>

</main>

<div role="complementary">

  <aside class="testimonials">

    <h1>What you say</h1>

    <?php perch_layout('testimonial'); ?>

  </aside>

  <?php perch_content('Subscribe'); ?>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>