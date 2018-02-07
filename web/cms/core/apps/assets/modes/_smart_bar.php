<?php
    $Settings = PerchSettings::fetch();

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
    $buckets = $Assets->get_available_buckets($CurrentUser);
    if ($Settings->get('assets_restrict_buckets')->val()) {
        if (isset($template_buckets) && PerchUtil::count($template_buckets)) {
            $buckets = array_intersect($template_buckets, $buckets);
        }    
    } 
    
    $bucket_options = [];
    if (PerchUtil::count($buckets)) {              
        foreach ($buckets as $bucket) {
            $Bucket = PerchResourceBuckets::get($bucket);
            $bucket_options[] = [
                'value' => $Bucket->get_name(),
                'title' => $Bucket->get_label(),
                'privs' => $CurrentUser->get_privs_for_bucket($Bucket->get_name()),
            ];
        }
    }



    // Tag filter
    $Tags = new PerchAssets_Tags();
    $tags = $Tags->get_top(40);
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
        'persist'=> ['show-filter', 'type', 'bucket', 'tag', 'q', 'buckets'],
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
        'persist' => ['view', 'q', 'buckets'],
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
        'persist' => ['view', 'q', 'buckets'],
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
        'persist' => ['view', 'q', 'buckets'],
        'options' => $tag_options,
        'actions' => [
                    [
                        'title'  => 'Clear',
                        'remove' => ['tag', 'show-filter'],
                        'icon'   => 'core/cancel',
                    ]
                ],
    ]);

    $Smartbar->add_item([
        'active' => false,
        'type'   => 'search',
        'data'   => [
            'search' => 'assets',
        ],
        'title'  => 'Search',
        'arg'    => 'q',
        'icon'   => 'core/search',
        'position' => 'end',
    ]);

    echo $Smartbar->render();

    echo '<script>if (Perch.UI.Assets) { ';       
    if (!(int)$Settings->get('assets_restrict_buckets')->val()) echo 'Perch.UI.Assets.setBucketPermissions(-1);';
    echo 'Perch.UI.Assets.setBucketOptions('.PerchUtil::json_safe_encode($bucket_options).');';
    echo ' }</script>';