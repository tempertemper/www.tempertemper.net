<?php
  $page = 'resource';
  perch_layout('head', [
    'page' => $page,
  ]);
  perch_layout('header', [
    'page' => $page,
  ]);
  echo '<main role="main">';
  perch_collection('Resources', [
    'filter' => [
      [
        'filter' => 'slug',
        'match'  => 'eq',
        'value'  => perch_get('s'),
      ],
      [
        'filter' => 'status',
        'match'  => 'eq',
        'value'  => 'published',
      ],
    ]
  ]);
  echo '</main>';
  echo '<div role="complementary">';
  perch_content('Subscribe');
  echo '</div>';
  perch_layout('footer');
  perch_layout('end');
