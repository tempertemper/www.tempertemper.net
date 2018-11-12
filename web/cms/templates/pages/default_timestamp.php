<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main" id="main">';
perch_content('Primary content');
echo '</main>';
echo '<div role="complementary">';
echo '<aside>';
echo '<p>Document last updated:'.perch_page_modified([
  'format' => '%e %B %Y',
]).'</p>';
echo '</aside>';
echo '</div>';
perch_layout('footer');
perch_layout('end');
