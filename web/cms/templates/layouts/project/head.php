<!doctype html>

<!--[if lte IE 8 ]><html class="ie8" xml:lang="en" lang="en"><![endif]-->
<!--[if IE 9 ]><html class="ie9 ie" xml:lang="en" lang="en"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html xml:lang="en" lang="en"><!--<![endif]-->

<head>

  <?php
    perch_layout('_ie_specific');

    $project = perch_collection('Projects', [
      'filter'        => 'slug',
      'match'         => 'eq',
      'value'         => perch_get('s'),
      // 'count'         => 1,
      'skip-template' => 'true',
      'return-html'   => 'true',
    ]);

    $title       = $project['0']['title'];
    $description = strip_tags($project['0']['excerpt']);

    perch_page_attributes_extend(array(
      'description' => $description,
      'title'       => $title,
    ));

    perch_layout('_attributes');
    perch_layout('_mobile_specific');
    perch_layout('_apple_touch_icon');
    perch_layout('_favicon');
    perch_layout('_browser_styling');
    perch_get_css();
    perch_layout('_fonts');
    perch_layout('_google_sitename');
  ?>

</head>

<body class="<?php echo PerchUtil::urlify(perch_pages_navigation_text(true));?>">