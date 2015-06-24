<?php perch_layout('head'); ?>
<?php perch_layout('/services/header'); ?>

<main role="main">

  <?php
    if (perch_get('cat')) {
      perch_collection('Projects', [
        'category' => 'services/'.perch_get('cat'),
        'template' => 'project_cat_list.html',
        'paginate' => 'true',
        'count'    => 5
      ]);
    } else {
      perch_categories(array(
        'set'=>'services',
      ));
    }
  ?>

</main>

<?php
  perch_layout('footer');
  perch_layout('end');
?>