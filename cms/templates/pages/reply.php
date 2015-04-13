<?php perch_layout('start'); ?>
<?php perch_layout('header'); ?>

<main role="main">

  <?php perch_content('Primary content'); ?>

</main>

<div role="complementary">

  <section class="now-what">

    <h1>Now what?</h1>

    <p>Head <a href="/">back to the homepage</a> or have a read of one of these articles:</p>

    <?php
      $opts = array(
      'sort-order'=>'RAND',
      'count'=>'2',
      'category'=>array('resources'),
      'template'=>'blog/post_selection.html'
      );
      perch_blog_custom($opts);
    ?>

  </section>

</div>

<?php perch_layout('footer'); ?>
<?php perch_layout('end'); ?>