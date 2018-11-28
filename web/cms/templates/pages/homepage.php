<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main" id="main">';
perch_content('Introduction');
perch_blog_custom([
    'template'   => 'blog_teaser.html',
    'sort'       => 'postDateTime',
    'sort-order' => 'DESC',
    'count'      => 2,
]);
perch_content('Call to action');
echo '</main>';
perch_layout('footer');
perch_layout('end');
