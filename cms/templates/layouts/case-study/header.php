<header role="banner">

  <?php include(__DIR__ . '/../_logo.php') ?>
  <?php include(__DIR__ . '/../_nav_toggle.php') ?>
  <?php include(__DIR__ . '/../_primary_nav.php') ?>

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
