<?php
perch_blog_check_preview();
perch_layout('blog/head');
perch_layout('blog/post_header');
echo '<main role="main" class="entry-content">';
perch_blog_post(perch_get('s'));
echo '</main>';
echo '<div role="complementary">';
perch_layout('blog/post_details');
perch_content('Follow');
echo '</div>';
perch_layout('footer');
perch_layout('end');
