<?php perch_layout('/project/head'); ?>
<?php perch_layout('/project/header'); ?>

<main role="main">

  <?php

    echo '<aside class="details">';
    perch_collection('Projects', [
      'template' => 'project_details.html',
      'filter'   => 'slug',
      'match'    => 'eq',
      'value'    => perch_get('s'),
      'count'    => 1,
    ]);
    echo '</aside>';

    perch_collection('Projects', [
      'template' => 'project_detail.html',
      'filter'   => 'slug',
      'match'    => 'eq',
      'value'    => perch_get('s'),
      'count'    => 1,
    ]);

  ?>

  <?php perch_layout('call_to_action'); ?>
  <p><a href="/projects/" class="back">Back to full list of work</a></p>

</main>

<?php
  perch_layout('footer');
  perch_layout('end');
?>
