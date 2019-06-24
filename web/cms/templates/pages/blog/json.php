<?php
    include('../cms/runtime.php');

    $domain = 'https://'.$_SERVER['HTTP_HOST'];

    $feed = [
        'version'       => 'https://jsonfeed.org/version/1',
        'title'         => 'tempertemper blog',
        'home_page_url' => $domain,
        'feed_url'      => $domain.'/events/feed.json',
        'icon'          => $domain.'/cms/addons/feathers/tempertemper/img/icons/apple-touch-icon-180x180.png',
        'favicon'       => $domain.'/cms/addons/feathers/tempertemper/img/icons/favicon.png',
        'items'         => [],
    ];

    $items = perch_blog_custom([
        'skip-template' => true,
        'count'         => 10,
        'sort'          => 'postDateTime',
        'sort-order'    => 'DESC'
    ]);

    if (count($items)) {
        foreach($items as $item) {
            $feed['items'][] = [
                'id'             => $item['postID'],
                'date_published' => date('c', strtotime($item['postDateTime'])),
                'title'          => $item['postTitle'],
                'content_html'   => $item['excerpt'],
                'url'            => $domain.'/events/'.$item['postURL'],
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($feed);
