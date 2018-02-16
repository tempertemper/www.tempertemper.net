<?php
  perch_layout('head');
  perch_layout('header');
  echo '<main role="main">';
  perch_content('Introduction');
  perch_collection('Projects', [
    'template'   => 'project_list.html',
    'paginate'   => 'true',
    'count'      => 5,
  ]);
  echo '</main>';
  perch_layout('footer');
  perch_layout('end');
