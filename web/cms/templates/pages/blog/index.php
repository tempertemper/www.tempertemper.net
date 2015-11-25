<?php
  perch_layout('head');
  perch_layout('header');
?>

<div role="main">

  <?php
    perch_blog_custom(array(
      'template'   => 'post_in_list.html',
      'sort'       => 'postDateTime',
      'sort-order' => 'DESC',
      'paginate'   => true,
      'count'      => 10,
      'blog'       => 'resources',
    ));
  ?>

</div>

<div role="complementary">

  <aside class="post-groups">

    <h1>Post groups</h1>

    <?php
      perch_blog_categories(array(
        'sort'       => 'catTitle',
        'sort-order' => 'ASC',
      ));
      perch_blog_date_archive_years();
      perch_blog_tags();
    ?>

  </aside>

</div>

<?php
  perch_layout('footer');
  perch_layout('end');
?>