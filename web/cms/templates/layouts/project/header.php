<header role="banner">

  <?php
    perch_layout('_logo');
    perch_layout('_nav_toggle');
    perch_layout('_primary_nav');
  ?>

  <h1>
    <?php perch_collection('Projects', [
      'filter' => 'slug',
      'match' => 'eq',
      'value' => perch_get('s'),
      'count' => 1,
      'template'=>'/project/page_header.html',
    ]); ?>
  </h1>

</header>
