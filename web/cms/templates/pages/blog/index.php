<?php
  perch_layout('head');
  perch_layout('header');
  echo '<main role="main">';
  perch_content('Introduction');
  perch_blog_custom([
    'template'   => 'post_in_list.html',
    'sort'       => 'postDateTime',
    'sort-order' => 'DESC',
    'paginate'   => true,
    'count'      => 10,
  ]);
  echo '</main>';
  echo '<div role="complementary">';
  echo '<aside class="post-groups">';
  echo '<h1>Post groups</h1>';
  perch_blog_categories([
  'sort'       => 'catTitle',
  'sort-order' => 'ASC',
  ]);
  perch_blog_date_archive_years();
  perch_blog_tags();
  echo '</aside>';
  echo '</div>';
  perch_layout('footer');
  perch_layout('end');
