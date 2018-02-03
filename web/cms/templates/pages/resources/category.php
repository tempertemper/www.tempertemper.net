<?php
  perch_layout('head');
  $page = 'resource_category';
  perch_layout('header', [
    'page' => $page,
  ]);
  echo '<main role="main">';

  if (perch_get('cat')) {
    perch_collection('Resources', [
      'template'   => 'resource_list.html',
      'paginate'   => 'true',
      'count'      => 10,
      'sort'       => 'date',
      'sort-order' => 'DESC',
      'filter'     => 'status',
      'match'      => 'eq',
      'value'      => 'published',
      'category'   => 'resources/'.perch_get('cat'),
    ]);
  } else {
    perch_categories([
      'set' => 'resources',
    ]);
  }

  echo '</main>';
  perch_layout('footer');
  perch_layout('end');
