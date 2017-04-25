<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Listing all collections'),
        'button'  => [
                        'text' => $Lang->get('Add collection'),
                        'link' => '/core/apps/content/manage/collections/edit',
                        'icon' => 'core/plus',
                        'priv' => 'content.collections.create',
                    ]
        ], $CurrentUser);

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
            'active' => true,
            'title'  => 'Collections',
            'link'   => '/core/apps/content/manage/collections/',
        ]);
    echo $Smartbar->render();

    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Title',
            'value'     => 'collectionKey',
            'sort'      => 'collectionKey',
            'edit_link' => '../../collections',
            'priv'      => 'content.collections.manage',
        ]);
    $Listing->add_col([
            'title'     => 'Items',
            'value'     => 'get_item_count'
        ]);
    $Listing->add_col([
            'title'     => 'Updated',
            'value'     => function($item){
                    return PerchUI::format_date($item->collectionUpdated());
            },
            'sort'      => 'collectionUpdated',
        ]);
    $Listing->add_delete_action([
            'priv'   => 'content.collections.delete',
            'inline' => true,
            'path'   => 'delete'
        ]);


    echo $Listing->render($collections);
