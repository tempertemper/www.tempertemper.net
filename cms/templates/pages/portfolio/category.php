<?php include($_SERVER['DOCUMENT_ROOT'].'/cms/runtime.php'); ?>

<?php perch_layout('start'); ?>
<?php perch_layout('/case-study/header.category'); ?>

<main role="main">

  <?php
    if (perch_get('cat')) {
      perch_content_custom('Portfolio', array(
        'category' => perch_get('portfolio/'.perch_get('cat').'/'),
        'page'=>'/portfolio/index.php',
        'template' => 'portfolio_list.html',
        'paginate'=>'true',
        'count'=>5
      ));
    } else {
      perch_categories(array(
        'set'=>'portfolio',
      ));
    }
  ?>

  <?php
    if (!perch_get('cat')) {
      echo('hello2');
    }
  ?>


</main>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>