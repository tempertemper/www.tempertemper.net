<header role="banner">

  <?php include(__DIR__ . '/../_logo.php') ?>
  <?php include(__DIR__ . '/../_nav_toggle.php') ?>
  <?php include(__DIR__ . '/../_primary_nav.php') ?>

  <h1>
    <?php
      if (perch_get('cat')) {
        perch_category('work/'.perch_get('cat').'/',array(
          'template'=>'category_header.html'
        ));
      } else {
        echo('Types of Work');
      }
    ?>
  </h1>

</header>
