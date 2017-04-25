<?php

    echo $HTML->title_panel([
        'heading' => $Lang->get($spam ? 'Listing spam responses' : 'Listing responses'),
    ], $CurrentUser);
    

    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
        'active' => $filter=='all',
        'title' => 'All Responses',
        'link'  => $API->app_nav().'/responses/?id='.$Form->id(),
        'icon'  => 'core/o-documents',
    ]);

    $Smartbar->add_item([
        'active' => $filter=='spam',
        'title' => 'Spam',
        'link'  => $API->app_nav().'/responses/?id='.$Form->id().'&spam=1',
        'icon'  => 'ext/o-poop',
    ]);

    $Smartbar->add_item([
        'active' => false,
        'title' => 'Form Options',
        'link'  => $API->app_nav().'/settings/?id='.$Form->id(),
        'priv'  => 'perch_forms.configure',
        'icon'  => 'core/o-toggles',
    ]);

    $Smartbar->add_item([
        'active' => false,
        'title' => 'Download CSV',
        'link'  => $API->app_nav().'/responses/export/?id='.$Form->id(),
        'priv'  => 'perch_forms.configure',
        'icon'  => 'ext/o-cloud-download',
        'position' => 'end',
    ]);



    echo $Smartbar->render();


    
    if (PerchUtil::count($responses)) {


        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    
        $Listing->add_col([
                'title'     => 'Response',
                'value'     => function($item) {
                    return '# '.$item->id();
                },
                'sort'      => 'responseID',
                'edit_link' => 'detail',
            ]);

        $Listing->add_col([
                'title'     => 'Date',
                'value'     => 'responseCreated',
                'sort'      => 'responseCreated',
                'format'    => [
                                'type' => 'date',
                                'format' => PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT
                                ],
            ]);

        $Listing->add_col([
                'title'     => 'Detail',
                'value'     => function($Response) use ($HTML, $Lang) {

                    $details = PerchUtil::json_safe_decode($Response->responseJSON(), true);
                        $out = array();
                        if (PerchUtil::count($details)) {
                            foreach($details['fields'] as $item) {
                                if (isset($item['attributes']['label'])) {
                                    $out[] = '<strong>'.$HTML->encode($item['attributes']['label']).':</strong> '.$HTML->encode(PerchUtil::excerpt($item['value'], 10));
                                }
                            }
                            $out[] = '<a href="'.PERCH_LOGINPATH.'/addons/apps/perch_forms/responses/detail/?id='.$Response->id().'">'.$Lang->get('View response...').'</a>';
                            return implode('<br />', $out);
                        }

                },
            ]);
        
        $Listing->add_delete_action([
                'priv'   => 'perch.users.roles.delete',
                'inline' => true,
                'path'   => 'delete',
            ]);

        echo $Listing->render($responses);


    } // if responses
    
     
