<?php perch_layout('/case-study/head'); ?>
<?php perch_layout('/case-study/header'); ?>

<main role="main">

  <?php
    perch_collection('Work', [
      'template' => 'work_detail.html',
      'filter'   => 'slug',
      'match'    => 'eq',
      'value'    => perch_get('s'),
      'count'    => 1,
    ]);

    perch_collection('Work', [
      'template' => 'work_details.html',
      'filter'   => 'slug',
      'match'    => 'eq',
      'value'    => perch_get('s'),
      'count'    => 1,
    ]);
  ?>

  <?php perch_content('Call to action'); ?>

</main>

<?php
  perch_layout('footer');
  perch_layout('end');
?>