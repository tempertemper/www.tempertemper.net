<!doctype html>

<!--[if lte IE 8 ]><html class="ie8" xml:lang="en" lang="en"><![endif]-->
<!--[if IE 9 ]><html class="ie9 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->

<head>

  <?php perch_layout('_ie_specific'); ?>

  <title><?php perch_pages_title(); ?></title>
  <meta name="description" content="<?php perch_page_attribute('description'); ?>" />

  <?php
    perch_layout('_mobile_specific');
    perch_layout('_apple_touch_icon');
    perch_layout('_favicon');
  ?>

  <meta property="og:title" content="tempertemper Web Design" />
  <meta property="og:site_name" content="tempertemper Web Design"/>
  <meta property="og:url" content="https://tempertemper.net<?php echo PerchSystem::get_page() ?>" />
  <meta property="og:image" content="https://tempertemper.net/assets/images/tempertemper-web-design-logo-facebook.jpg" />
  <meta property="og:description" content="<?php perch_page_attribute('description'); ?>" />

  <meta name="twitter:card" content="summary"/>
  <meta name="twitter:site" content="@tempertemper"/>
  <meta name="twitter:domain" content="<?php perch_page_attribute('description'); ?>"/>
  <meta name="twitter:creator" content="@tempertemper"/>
  <meta name="twitter:url" content="<?php
    perch_page_url(array(
      'hide-extensions'    => true,
      'hide-default-doc'   => true,
      'add-trailing-slash' => false,
      'include-domain'     => true,
    ));
  ?>" />

  <?php
    perch_get_css();
    perch_layout('_fonts');
    perch_layout('_google_sitename');
  ?>

</head>

<body class="<?php echo PerchUtil::urlify(perch_pages_navigation_text(true));?>">