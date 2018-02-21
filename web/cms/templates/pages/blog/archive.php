<?php
perch_layout('head');
perch_layout('blog/archive');
echo '</main>';
echo '<div role="complementary">';
echo '<aside class="post-groups">';
echo '<h2>Post groups</h2>';
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
