<!doctype html>

<!--[if lte IE 8 ]><html class="ie8" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->

<head>

  <?php
    perch_layout('_ie_specific');
    perch_layout('_attributes');
    perch_layout('_mobile_specific');
    perch_layout('_apple_touch_icon');
    perch_layout('_favicon');
    perch_layout('_browser_styling');
    perch_get_css();
    perch_layout('_fonts');
    perch_layout('_google_sitename');
  ?>

  <link rel="alternate" type="application/rss+xml" title="RSS" href="/blog/rss" />

</head>

<body class="<?php echo PerchUtil::urlify(perch_pages_navigation_text(true));?>">