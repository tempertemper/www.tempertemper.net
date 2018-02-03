<?php
  perch_layout('head');
  perch_layout('header');
  echo '<main role="main">';
  perch_collection('Resources', [
    'template'   => 'resource_list.html',
    'paginate'   => 'true',
    'count'      => 10,
    'sort'       => 'date',
    'sort-order' => 'DESC',
    'filter'     => 'status',
    'match'      => 'eq',
    'value'      => 'published',
  ]);
  echo '</main>';
  perch_layout('footer');
  perch_layout('end');
