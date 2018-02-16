<?php
  perch_layout('head');
  perch_layout('header');
?>

<main role="main">

  <?php perch_content('Introduction'); ?>

  <?php
    perch_collection('Projects', [
      'template' => 'project_teaser.html',
      'count'=>2,
    ]);
  ?>

  <aside class="testimonials">

    <h1>Testimonials</h1>

    <?php
      perch_collection('Testimonials', [
        'sort'=>'citation',
        'sort-order'=>'RAND',
        'count'=>1,
        'template'=>'testimonial_feature.html',
        'filter' => 'featured',
        'match' => 'eq',
        'value' => 'featured',
      ]);
    ?>

  </aside>

  <?php perch_layout('call_to_action'); ?>

</main>

<?php
  perch_layout('footer');
  perch_layout('end');
?>
