<!doctype html>

<!--[if lte IE 8 ]><html class="ie8" xml:lang="en" lang="en"><![endif]-->
<!--[if IE 9 ]><html class="ie9 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->

<head>

  <?php include(__DIR__ . '/../partials/_ie_specific.php') ?>

  <title><?php perch_blog_post_field(perch_get('s'), 'postTitle'); ?></title>

  <meta name="description" content="<?php perch_blog_post_field(perch_get('s'), 'excerpt'); ?>" />

  <?php include(__DIR__ . '/../partials/_mobile_specific.php') ?>

  <?php include(__DIR__ . '/../partials/_apple_touch_icon.php') ?>
  <?php include(__DIR__ . '/../partials/_favicon.php') ?>

  <meta property="og:title" content="<?php perch_blog_post_field(perch_get('s'), 'postTitle'); ?>" />
  <meta property="og:site_name" content="tempertemper Web Design"/>
  <meta property="og:url" content="https://tempertemper.net/blog/<?php perch_blog_post_field(perch_get('s'), 'postSlug'); ?>" />
  <meta property="og:image" content="<?php
    perch_blog_custom(array(
      'template' => 'featured_image.html',
      'filter'=>'postSlug',
      'match'=>'eq',
      'value'=> perch_get('s'),
    ));
  ?>" />
  <meta property="og:description" content="<?php perch_blog_post_field(perch_get('s'), 'excerpt'); ?>" />

  <meta name="twitter:card" content="summary"/>
  <meta name="twitter:site" content="@tempertemper"/>
  <meta name="twitter:domain" content="<?php perch_blog_post_field(perch_get('s'), 'excerpt'); ?>"/>
  <meta name="twitter:creator" content="@tempertemper"/>
  <meta name="twitter:url" content="https://tempertemper.net/blog/<?php perch_blog_post_field(perch_get('s'), 'postSlug'); ?>">

  <link href="/blog/rss" rel="alternate" type="application/rss+xml" title="RSS" />

  <?php
    perch_get_css();
    perch_layout('_fonts');
  ?>
  <?php perch_layout('_google_sitename'); ?>

</head>

<body class="blog-post <?php perch_blog_post_field(perch_get('s'), 'postSlug'); ?>" class="hentry">