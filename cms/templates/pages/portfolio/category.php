<?php perch_layout('start'); ?>
<?php perch_layout('/case-study/header.category'); ?>

<main role="main">

  <?php
    if (perch_get('cat')) {
      perch_collection('Work', [
        'category' => perch_get('work/'.perch_get('cat').'/'),
        'template' => 'work_list.html',
        'paginate'=>'true',
        'count'=>5
      ]);
    } else {
      perch_categories(array(
        'set'=>'work',
      ));
    }
  ?>

</main>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>