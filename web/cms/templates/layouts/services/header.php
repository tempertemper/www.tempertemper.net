<header role="banner">

  <?php
    perch_layout('_logo');
    perch_layout('_nav_toggle');
    perch_layout('_primary_nav');
  ?>

  <h1>
    <?php
      if (perch_get('cat')) {
        perch_category('services/'.perch_get('cat').'/',array(
          'template'=>'category_header.html'
        ));
      } else {
        echo('Services');
      }
    ?>
  </h1>

</header>
