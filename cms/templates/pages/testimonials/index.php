<?php
  perch_layout('head');
  perch_layout('header');
?>

<main role="main">

  <?php
    perch_collection('Testimonials', array(
      'template'=>'testimonial_list.html',
      'paginate'=>'true',
      'count'=>10,
      'sort'=>'date',
      'sort-order'=>'DESC'
    ));
  ?>

</main>

<?php
  perch_layout('footer');
  perch_layout('end');
?>