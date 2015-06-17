<?php perch_layout('head'); ?>
<?php perch_layout('blog/archive_header'); ?>

<?php perch_layout('blog/archive'); ?>

</main>

<div role="complementary">

  <aside class="post-groups">

    <h1>Post groups</h1>

    <?php perch_blog_categories(); ?>
    <?php perch_blog_date_archive_years(); ?>
    <?php perch_blog_tags(); ?>

  </aside>

</div>

<?php
  perch_layout('footer');
  perch_layout('end');
?>