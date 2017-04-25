<?php 

    echo $HTML->title_panel([
        'heading' => $Lang->get('Listing all user roles'),
        'button'  => [
                        'text' => $Lang->get('Add role'),
                        'link' => '/core/users/roles/edit/',
                        'icon' => 'core/plus'
                    ]
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Roles',
        'link'     => '/core/users/roles/',
    ]);
    echo $Smartbar->render();


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Role',
            'value'     => 'roleTitle',
            'sort'      => 'roleTitle',
            'edit_link' => 'edit',
        ]);
    
    $Listing->add_delete_action([
            'priv'   => 'perch.users.roles.delete',
            'inline' => true,
            'path'   => 'delete',
            'display'=> function($Item) {
                return !$Item->roleMasterAdmin();
            },
        ]);

    echo $Listing->render($roles);
