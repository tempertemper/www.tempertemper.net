<?php include(__DIR__ . '/../partials/_primary_nav.php') ?>

<header role="banner">

  <?php perch_search_form(); ?>
  <?php include(__DIR__ . '/../partials/_nav_toggle.php') ?>
  <?php include(__DIR__ . '/../partials/_logo.php') ?>

  <h1>
    <?php
      if (perch_get('cat')) {
        perch_category('portfolio/'.perch_get('cat').'/',array(
          'template'=>'category_header.html'
        ));
      } else {
        echo('<a href="/portfolio/">Portfolio</a>: Categories');
      }
    ?>
  </h1>

</header>
