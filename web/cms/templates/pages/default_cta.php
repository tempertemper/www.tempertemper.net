<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main" id="main">';
perch_content('Primary content');
perch_content('Call to action');
echo '</main>';
perch_layout('footer');
perch_layout('end');
