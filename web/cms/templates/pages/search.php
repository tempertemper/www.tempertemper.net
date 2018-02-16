<?php
  perch_layout('head');
  perch_layout('search_header');
  $query = perch_get('q');
  perch_content_search($query, [
    'count'=>5,
    'hide-extensions'=>'true',
    'hide-default-doc'=>'true'
  ]);
  echo '</main>';
  echo '<div role="complementary">';
  echo '<aside>';
  echo '<h1>Search again</h1>';
  perch_search_form();
  echo '</aside>';
  echo '</div>';
  perch_layout('footer');
  perch_layout('end');
