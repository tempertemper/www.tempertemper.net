<?php
  perch_layout('/service/head');
  perch_layout('/service/header');

  echo '<main role="main">';

  perch_collection('Services', [
    'template' => 'service_detail.html',
    'filter'   => 'slug',
    'match'    => 'eq',
    'value'    => perch_get('s'),
    'count'    => 1,
  ]);

  perch_layout('call_to_action');
  echo '<p><a href="/services/" class="back">Back to full list of services</a></p>';
  echo '</main>';

  perch_layout('footer');
  perch_layout('end');
