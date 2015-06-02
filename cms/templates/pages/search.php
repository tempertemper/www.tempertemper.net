<?php perch_layout('head'); ?>
<?php perch_layout('search_header'); ?>

  <?php
    $query = perch_get('q');
    perch_content_search($query, array(
      'count'=>5,
      'hide-extensions'=>'true',
      'hide-default-doc'=>'true'
    ));
  ?>

  <div role="complementary">

    <aside class="testimonials">

      <h1>Search again</h1>

      <?php perch_search_form(); ?>

    </aside>

  </div>

</main>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>