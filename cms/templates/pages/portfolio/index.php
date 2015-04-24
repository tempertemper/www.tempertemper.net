<?php perch_layout('start'); ?>
<?php perch_layout('header'); ?>

<main role="main">

  <?php
    perch_collection('Work', [
      'template'   => 'portfolio_list.html',
      'paginate'   => 'true',
      'count'      => 5
    ]);
  ?>

</main>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>