<?php perch_layout('start'); ?>
<?php perch_layout('header'); ?>

<main role="main">

  <?php perch_content('Primary content'); ?>

</main>

<div role="complementary">

  <aside>

    <p>Document last updated: <?php
        perch_page_modified(array(
          'format' => '%d %B %Y',
        ));
      ?></p>

  </aside>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>