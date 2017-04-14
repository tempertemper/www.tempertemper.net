<?php
    echo $HTML->title_panel([
        'heading' => $Lang->get('Listing all user accounts'),
        'button'  => [
                        'text' => $Lang->get('Add user'),
                        'link' => '/core/users/add/',
                        'icon' => 'core/plus'
                    ]
        ]);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
    $Smartbar->add_item([
        'active'   => true,
        'title'    => 'Users',
        'link'     => '/core/users/',
        'icon'     => 'core/users',
    ]);
    $Smartbar->add_item([
        'active'   => false,
        'title'    => 'My Account',
        'link'     => '/core/account/',
        'icon'     => 'core/user',
    ]);
    echo $Smartbar->render();


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Username',
            'value'     => 'userUsername',
            'sort'      => 'userUsername',
            'gravatar'  => 'userEmail',
            'edit_link' => 'edit',
        ]);
    $Listing->add_col([
            'title' => 'Name',
            'value' => function($item, $HTML) { 
                    return $HTML->encode($item->userGivenName().' '.$item->userFamilyName()); 
                },
            'sort'  => 'userFamilyName',
        ]);
    $Listing->add_col([
            'title' => 'Role',
            'value' => function($item, $HTML, $Lang) {
                    if ($item->userMasterAdmin()) {
                        return $HTML->wrap('strong', $HTML->encode(PerchLang::get('Primary Admin')));
                    }else{
                        return $HTML->encode($Lang->get($item->roleTitle()));
                    }
                },
            'sort'  => 'roleTitle',
        ]);
    $Listing->add_col([
            'title' => 'Email',
            'value' => function($item, $HTML) {
                    return $HTML->wrap('a[href=mailto:'.$item->userEmail().']', $HTML->encode($item->userEmail()));
                },
            'sort'  => 'userEmail',
        ]);
    $Listing->add_col([
            'title' => 'Last login',
            'value' => function($item, $HTML) {
                    return $HTML->encode(strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT, strtotime($item->userLastLogin())));
                },
            'sort'  => 'userLastLogin',
        ]);
    $Listing->add_delete_action([
            'priv'   => 'perch.users.manage',
            'inline' => true,
            'path'   => 'delete',
            'display' => function($item) use ($CurrentUser){
                return ($item->id()!=$CurrentUser->id() && !$item->userMasterAdmin());
            }
        ]);

    echo $Listing->render($users);
