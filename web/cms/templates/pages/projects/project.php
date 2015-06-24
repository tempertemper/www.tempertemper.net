<?php perch_layout('/project/head'); ?>
<?php perch_layout('/project/header'); ?>

<main role="main">

  <?php
    perch_collection('Projects', [
      'template' => 'project_detail.html',
      'filter'   => 'slug',
      'match'    => 'eq',
      'value'    => perch_get('s'),
      'count'    => 1,
    ]);

    PerchSystem::set_var('modified', perch_page_modified(array(
      'format' => '%e %B %Y',
    ), true));

    perch_collection('Projects', [
      'template' => 'project_details.html',
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