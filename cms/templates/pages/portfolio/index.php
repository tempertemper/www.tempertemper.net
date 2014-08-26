<?php include($_SERVER['DOCUMENT_ROOT'].'/cms/runtime.php'); ?>

<?php perch_layout('start'); ?>
<?php perch_layout('header'); ?>

<main role="main">

  <?php
    perch_content_custom('Portfolio', array(
      'template' => 'portfolio_list.html',
      'paginate'=>'true',
      'count'=>5
    ));
  ?>

</main>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>