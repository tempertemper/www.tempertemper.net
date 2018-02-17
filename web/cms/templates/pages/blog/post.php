<?php
$page = 'blog_post';
perch_layout('head', [
    'page' => $page,
]);
perch_layout('header', [
    'page' => $page,
]);
perch_blog_check_preview();
echo '<main role="main" class="entry-content">';
perch_blog_post(perch_get('s'));
echo '</main>';
echo '<div role="complementary">';
perch_layout('blog/post_details');
perch_content('Follow');
echo '</div>';
perch_layout('footer');
perch_layout('end');
