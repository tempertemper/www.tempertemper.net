<?php
  perch_layout('head');
  perch_layout('header');
?>

<main role="main">

  <?php perch_content('Primary content'); ?>

</main>

<div role="complementary">

  <aside>

    <p>Document last updated: <?php
        perch_page_modified([
          'format' => '%e %B %Y',
        ]);
      ?></p>

  </aside>

</div>

<?php
  perch_layout('footer');
  perch_layout('end');
?>
