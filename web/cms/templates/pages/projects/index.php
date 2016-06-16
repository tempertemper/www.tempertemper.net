<?php
  perch_layout('head');
  perch_layout('header');
?>

<main role="main">

  <?php perch_content('Introduction'); ?>

  <?php
    perch_collection('Projects', [
      'template'   => 'project_list.html',
      'paginate'   => 'true',
      'count'      => 5,
    ]);
  ?>

</main>

<?php
  perch_layout('footer');
  perch_layout('end');
?>
