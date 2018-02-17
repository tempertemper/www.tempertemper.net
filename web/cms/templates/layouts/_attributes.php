<?php
$domain        = 'https://'.$_SERVER["HTTP_HOST"];
$url           = $domain.$_SERVER["REQUEST_URI"];
$sitename      = "tempertemper Web Design";
$twittername   = "@tempertemper";
$sharing_image = perch_path('feathers/tempertemper', false, true).'/img/tempertemper-web-design-logo-facebook.jpg';
$author        = "Martin Underhill";
$title         = perch_pages_title(true);
$type          = 'website';

PerchSystem::set_vars([
    'domain'        => $domain,
    'url'           => $url,
    'sharing_image' => $sharing_image,
    'twittername'   => $twittername,
    'sitename'      => $sitename,
    'author'        => $author,
    'title'         => $title,
    'type'          => $type,
]);

perch_page_attributes([
'template' => 'default.html'
]);
