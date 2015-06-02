<?php
  perch_layout('head');
  perch_layout('header');
?>

<div role="main">

  <?php
    perch_blog_custom(array(
      'template'    =>  'post_in_list.html',
      'sort'        =>  'postDateTime',
      'sort-order'  =>  'DESC',
      'paginate'    =>  true,
      'count'       =>  10,
    ));
  ?>

</div>

<div role="complementary">

  <aside>

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