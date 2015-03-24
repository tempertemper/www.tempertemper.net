<!doctype html>

<!--[if lte IE 8 ]><html class="ie8" xml:lang="en" lang="en"><![endif]-->
<!--[if IE 9 ]><html class="ie9 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->

<head>

  <?php include('partials/_ie_specific.php') ?>

  <title><?php perch_pages_title(); ?></title>
  <meta name="description" content="<?php perch_page_attribute('description'); ?>" />

  <?php include('partials/_mobile_specific.php') ?>

  <?php include('partials/_apple_touch_icon.php') ?>
  <?php include('partials/_favicon.php') ?>

  <meta property="og:title" content="tempertemper Web Design" />
  <meta property="og:site_name" content="tempertemper Web Design"/>
  <meta property="og:url" content="http://tempertemper.net<?php echo PerchSystem::get_page() ?>" />
  <meta property="og:image" content="http://tempertemper.net/assets/images/tempertemper-web-design-logo-facebook.jpg" />
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

	<?php perch_get_css(); ?>

</head>

<body class="<?php echo PerchUtil::urlify(perch_pages_navigation_text(true));?>">