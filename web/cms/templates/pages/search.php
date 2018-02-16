<?php perch_layout('head'); ?>
<?php perch_layout('search_header'); ?>

<?php
  $query = perch_get('q');
  perch_content_search($query, [
    'count'=>5,
    'hide-extensions'=>'true',
    'hide-default-doc'=>'true'
  ]);
?>

</main>

<div role="complementary">

  <aside>

    <h1>Search again</h1>

    <?php perch_search_form(); ?>

  </aside>

</div>

<?php
  perch_layout('footer');
  perch_layout('end');
?>
