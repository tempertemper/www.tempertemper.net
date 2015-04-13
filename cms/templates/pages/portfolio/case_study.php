<?php perch_layout('/case-study/start'); ?>
<?php perch_layout('/case-study/header'); ?>

<main role="main">

  <?php
    perch_collection('Work', [
      'template' => 'portfolio_detail.html',
      'filter'   => 'slug',
      'match'    => 'eq',
      'value'    => perch_get('s'),
      'count'    => 1,
    ]);
  ?>

</main>

<div role="complementary">

  <aside>

    <h1>More case studies</h1>

    <?php
      perch_collection('Work', [
        'template' => 'portfolio_list_short.html',
      ]);
    ?>

  </aside>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>