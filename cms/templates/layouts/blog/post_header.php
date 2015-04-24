
<header role="banner">

  <?php perch_layout('_logo'); ?>
  <?php perch_layout('_nav_toggle'); ?>
  <?php perch_layout('_primary_nav'); ?>

  <h1 class="entry-title">
    <a href="/blog/">Blog</a>: <?php perch_blog_post_field(perch_get('s'), 'postTitle'); ?>
  </h1>

</header>
