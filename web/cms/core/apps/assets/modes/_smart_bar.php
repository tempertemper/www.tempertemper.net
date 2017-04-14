<?php

    // Type filter
    $types = $Assets->get_available_types();
    $type_options = [];

    if (PerchUtil::count($types)) {              
        $items = [];
        $group_types = PerchAssets_Asset::get_type_map();
        foreach ($group_types as $type=>$val) {
            $type_options[] = array(
                'value'  => $type,
                'title' => $val['label'],
            );
        }
        foreach ($types as $type) {
            $type_options[] = array(
                'value'   => $type,
                'title' => strtoupper($type),
            );
        }
    }


    // Bucket filter
    $buckets = $Assets->get_available_buckets();
    $bucket_options = [];
    if (PerchUtil::count($buckets)) {              
        foreach ($buckets as $bucket) {
            $bucket_options[] = array(
                'value'   => $bucket,
                'title' => ucfirst($bucket),
            );
        }
    }

    // Tag filter
    $Tags = new PerchAssets_Tags();
    $tags = $Tags->all();
    $tag_options = [];
    if (PerchUtil::count($tags)) {
        foreach($tags as $Tag) {
            $tag_options[] = [
                'value' => $Tag->tagSlug(),
                'title' => $Tag->tagTitle(),
            ];
        }
    } 
    
    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'type'   => 'toggle',
        'arg'    => 'view',
        'persist'=> ['show-filter', 'type', 'bucket', 'tag'],
        'options'=> [
                        [
                            'title' => 'Grid',
                            'icon'  => 'core/grid-big',
                            'value' => 'grid',
                        ],
                        [
                            'title' => 'List',
                            'icon'  => 'core/menu',
                            'value' => 'list',
                        ],
                    ],
    ]);

    $Smartbar->add_item([
        'id'      => 'atf',
        'title'   => 'By Type',
        'icon'    => 'assets/o-photo',
        'active'  => PerchRequest::get('type'),
        'type'    => 'filter',
        'arg'     => 'type',
        'persist' => ['view'],
        'options' => $type_options,
        'actions' => [

                ],
    ]);

    $Smartbar->add_item([
        'id'      => 'bf',
        'title'   => 'By Bucket',
        'icon'    => 'core/box-storage',
        'active'  => PerchRequest::get('bucket'),
        'type'    => 'filter',
        'arg'     => 'bucket',
        'persist' => ['view'],
        'options' => $bucket_options,
        'actions' => [

                ],
    ]);

    $Smartbar->add_item([
        'id'      => 'tf',
        'title'   => 'By Tag',
        'icon'    => 'core/tag',
        'active'  => PerchRequest::get('tag'),
        'type'    => 'filter',
        'arg'     => 'tag',
        'persist' => ['view'],
        'options' => $tag_options,
        'actions' => [
                    [
                        'title'  => 'Clear',
                        'remove' => ['tag', 'show-filter'],
                        'icon'   => 'core/cancel',
                    ]
                ],
    ]);

/*
    $Smartbar->add_item([
        'active' => false,
        'title'  => 'Search',
        'link'   => '/core/apps/assets/search/',
        'icon'   => 'core/search',
        'position' => 'end',
    ]);
*/
    echo $Smartbar->render();
