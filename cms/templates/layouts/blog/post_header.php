
<header role="banner">

  <?php include(__DIR__ . '/../_logo.php') ?>
  <?php include(__DIR__ . '/../_nav_toggle.php') ?>
  <?php include(__DIR__ . '/../_primary_nav.php') ?>

  <h1 class="entry-title">
    <a href="/blog/">Blog</a>: <?php perch_blog_post_field($_GET['s'], 'postTitle'); ?>
  </h1>

</header>
