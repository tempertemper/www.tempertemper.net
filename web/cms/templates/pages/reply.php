<?php
  perch_layout('head');
  perch_layout('header');
?>

<main role="main">

  <?php perch_content('Primary content'); ?>

</main>

<div role="complementary">

  <section class="now-what">

    <h1>Now what?</h1>

    <p>Head <a href="/">back to the homepage</a> or have a read of one of these articles:</p>

    <?php
      perch_blog_custom([
        'sort'=> 'postTitle',
        'sort-order'=>'RAND',
        'count'=>'3',
        'category'=>array('resources'),
        'template'=>'blog/post_teaser.html'
      ]);
    ?>

  </section>

</div>

<?php
  perch_layout('footer');
  perch_layout('end');
?>
