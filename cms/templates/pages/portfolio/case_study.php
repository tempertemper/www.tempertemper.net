<?php perch_layout('/case-study/head'); ?>
<?php perch_layout('/case-study/header'); ?>

<main role="main">

  <?php
    perch_collection('Work', [
      'template' => 'work_detail.html',
      'filter'   => 'slug',
      'match'    => 'eq',
      'value'    => perch_get('s'),
      'count'    => 1,
    ]);
  ?>

</main>

<div role="complementary">

  <aside>

    <h1>More work</h1>

    <?php
      perch_collection('Work', [
        'template' => 'work_list_short.html',
      ]);
    ?>

  </aside>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>