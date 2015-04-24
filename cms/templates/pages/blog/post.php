<?php
  perch_blog_check_preview();
  perch_layout('blog/start');
  perch_layout('blog/post_header');
?>

<main role="main" class="entry-content">

  <?php perch_blog_post(perch_get('s')); ?>

</main>

<div role="complementary">

  <?php perch_layout('blog/post_details'); ?>
  <?php perch_content('Blog subscribe'); ?>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>