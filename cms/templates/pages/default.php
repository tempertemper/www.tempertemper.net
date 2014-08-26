<?php include($_SERVER['DOCUMENT_ROOT'].'/cms/runtime.php'); ?>

<?php perch_layout('start'); ?>
<?php perch_layout('header'); ?>

<main role="main">

  <?php perch_content('Primary content'); ?>

</main>

<div role="complementary">

  <aside class="testimonials">

    <h1>What you say</h1>

    <?php include($_SERVER['DOCUMENT_ROOT'].'/cms/templates/layouts/testimonial.php'); ?>

  </aside>

  <?php perch_content('Subscribe'); ?>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>