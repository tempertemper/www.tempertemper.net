<?php include(__DIR__ . '/../partials/_primary_nav.php') ?>

<header role="banner">

  <?php perch_search_form(); ?>
  <?php include(__DIR__ . '/../partials/_nav_toggle.php') ?>
  <?php include(__DIR__ . '/../partials/_logo.php') ?>

  <h1>
    <a href="/portfolio/">Portfolio</a>: <?php perch_content_custom('Portfolio', array(
      'page'=>'/portfolio/index.php',
      'filter' => 'slug',
      'match' => 'eq',
      'value' => perch_get('s'),
      'count' => 1,
      'template'=>'/case-study/page_header.html',
    )); ?>
  </h1>

</header>
