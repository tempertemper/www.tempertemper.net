<?php include($_SERVER['DOCUMENT_ROOT'].'/cms/runtime.php'); ?>

<?php perch_layout('start'); ?>
<?php perch_layout('blog/archive_header'); ?>

<?php perch_layout('blog/archive'); ?>

</main>

<div role="complementary">

  <aside>

    <h1>Post groups</h1>

    <?php perch_blog_categories(); ?>
    <?php perch_blog_date_archive_years(); ?>
    <?php perch_blog_tags(); ?>

  </aside>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>