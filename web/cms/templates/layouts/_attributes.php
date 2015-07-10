<?php

  $domain        = 'https://'.$_SERVER["HTTP_HOST"];
  $url           = $domain.$_SERVER["REQUEST_URI"];
  $sitename      = "tempertemper Web Design";
  $twittername   = "@tempertemper";
  $sharing_image = '/cms/addons/feathers/tempertemper/img/tempertemper-web-design-logo-facebook.jpg';
  $author        = "Martin Underhill";
  $title         = perch_pages_title(true);
  $type         = 'website';

  PerchSystem::set_var('domain', $domain);
  PerchSystem::set_var('url', $url);
  PerchSystem::set_var('sharing_image', $sharing_image);
  PerchSystem::set_var('twittername', $twittername);
  PerchSystem::set_var('sitename', $sitename);
  PerchSystem::set_var('author', $author);
  PerchSystem::set_var('title', $title);
  PerchSystem::set_var('type', $type);

  perch_page_attributes(array(
    'template' => 'default.html'
  ));