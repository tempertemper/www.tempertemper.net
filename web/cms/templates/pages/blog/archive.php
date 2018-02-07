<?php
  perch_layout('head');
  perch_layout('blog/archive_header');
  perch_layout('blog/archive');
  echo '</main>';
  echo '<div role="complementary">';
  echo '<aside class="post-groups">';
  echo '<h1>Post groups</h1>';
  perch_blog_categories(array(
    'sort'       => 'catTitle',
    'sort-order' => 'ASC',
  ));
  perch_blog_date_archive_years();
  perch_blog_tags();
  echo '</aside>';
  echo '</div>';
  perch_layout('footer');
  perch_layout('end');
