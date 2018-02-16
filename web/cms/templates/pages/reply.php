<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main">';
perch_content('Primary content');
echo '</main>';
echo '<div role="complementary">';
echo '<section class="now-what">';
echo '<h1>Now what?</h1>';
echo '<p>Head <a href="/">back to the homepage</a> or have a read of one of these articles:</p>';
perch_blog_custom([
    'sort'=> 'postTitle',
    'sort-order'=>'RAND',
    'count'=>'3',
    'category'=>array('resources'),
    'template'=>'blog/post_teaser.html'
]);
echo '</section>';
echo '</div>';
perch_layout('footer');
perch_layout('end');
