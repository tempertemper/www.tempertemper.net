<!doctype html>

<!--[if lte IE 8 ]><html class="ie8" xml:lang="en" lang="en"><![endif]-->
<!--[if IE 9 ]><html class="ie9 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->

<head>

  <?php perch_layout('_ie_specific'); ?>

  <title>Project: <?php
    perch_collection('Projects', [
      'filter' => 'slug',
      'match' => 'eq',
      'value' => perch_get('s'),
      'count' => 1,
      'template'=>'/project/page_header.html',
    ]);
  ?></title>
  <meta name="description" content="<?php
    perch_collection('Projects', [
      'filter' => 'slug',
      'match' => 'eq',
      'value' => perch_get('s'),
      'count' => 1,
      'template'=>'/project/excerpt.html',
    ]);
  ?>" />

  <?php
    perch_layout('_mobile_specific');
    perch_layout('_apple_touch_icon');
    perch_layout('_favicon');
    perch_layout('_browser_styling');
  ?>

  <meta property="og:title" content="tempertemper Web Design" />
  <meta property="og:site_name" content="tempertemper Web Design"/>
  <meta property="og:url" content="https://tempertemper.net/projects/<?php
    perch_collection('Projects', [
      'filter' => 'slug',
      'match' => 'eq',
      'value' => perch_get('s'),
      'count' => 1,
      'template'=>'/project/slug.html',
    ]);
  ?>" />
  <meta property="og:image" content="https://tempertemper.net/img/tempertemper-web-design-logo-facebook.jpg" />
  <meta property="og:description" content="<?php
    perch_collection('Projects', [
      'filter' => 'slug',
      'match' => 'eq',
      'value' => perch_get('s'),
      'count' => 1,
      'template'=>'/project/excerpt.html',
    ]);
  ?>" />

  <meta name="twitter:card" content="summary"/>
  <meta name="twitter:site" content="@tempertemper"/>
  <meta name="twitter:domain" content="<?php
    perch_collection('Projects', [
      'filter' => 'slug',
      'match' => 'eq',
      'value' => perch_get('s'),
      'count' => 1,
      'template'=>'/project/excerpt.html',
    ]);
  ?>"/>
  <meta name="twitter:creator" content="@tempertemper"/>
  <meta name="twitter:url" content="https://tempertemper.net/projects/<?php
    perch_collection('Projects', [
      'filter' => 'slug',
      'match' => 'eq',
      'value' => perch_get('s'),
      'count' => 1,
      'template'=>'/project/slug.html',
    ]);
  ?>">

  <?php
    perch_get_css();
    perch_layout('_fonts');
    perch_layout('_google_sitename');
  ?>

</head>

<body class="<?php echo PerchUtil::urlify(perch_pages_navigation_text(true));?>">