<?php

    echo $HTML->title_panel([
        'heading' => $Lang->get('Listing forms'),
    ], $CurrentUser);

    if (PerchUtil::count($forms)) {


        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
        $Listing->add_col([
                'title'     => 'Form',
                'value'     => 'formTitle',
                'sort'      => 'formTitle',
                'edit_link' => 'responses',
            ]);

        $Listing->add_col([
                'title'     => 'Responses',
                'value'     => 'number_of_responses',
            ]);

        $Listing->add_col([
                'title'     => 'Most recent',
                'value'     => 'most_recent_response_date',
                'format'    => [
                                    'type'   => 'date',
                                    'format' => PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT,
                                ],
            ]);
        
        $Listing->add_delete_action([
                'priv'   => 'perch_forms.delete',
                'inline' => true,
                'path'   => 'delete',
            ]);

        echo $Listing->render($forms);

    }else{
        echo $HTML->warning_message('No forms have been submitted yet. Submit a new form to have it show up here.');
    }