<header role="banner">

  <?php
    perch_layout('_logo');
    perch_layout('_nav_toggle');
    perch_layout('_primary_nav');
  ?>

  <h1>
    <?php
      if (perch_layout_var('page', true) == 'resource_category') {
        echo 'Resource category: ';
        perch_category('resources/'.perch_get('cat').'/', [
          // 'template' => 'category_header.html',
        ]);
      } elseif (perch_layout_var('page', true) == 'resource') {
        $resource = perch_collection('Resources', [
          'filter'        => 'slug',
          'match'         => 'eq',
          'value'         => perch_get('s'),
          'skip-template' => 'true',
          'return-html'   => 'true',
        ]);
        $title = $resource['0']['title'];
        echo $title;
      } else {
        perch_content('Headline');
      };
    ?>
  </h1>

</header>