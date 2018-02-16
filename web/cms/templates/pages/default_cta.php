<?php
  perch_layout('head');
  perch_layout('header');
  echo '<main role="main">';
  perch_content('Primary content');
  perch_layout('call_to_action');
  echo '</main>';
  perch_layout('footer');
  perch_layout('end');
