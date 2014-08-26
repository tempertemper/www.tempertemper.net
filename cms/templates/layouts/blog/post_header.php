<?php include(__DIR__ . '/../partials/_primary_nav.php') ?>

<header role="banner">

  <?php perch_search_form(); ?>
  <?php include(__DIR__ . '/../partials/_nav_toggle.php') ?>
  <?php include(__DIR__ . '/../partials/_logo.php') ?>

  <h1 class="entry-title">
    <a href="/blog/">Blog</a>: <?php perch_blog_post_field($_GET['s'], 'postTitle'); ?>
  </h1>

</header>
