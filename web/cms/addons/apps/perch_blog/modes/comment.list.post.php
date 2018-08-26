<?php

    echo $HTML->title_panel([
            'heading' => $Lang->get('Listing comments'),
            ], $CurrentUser);
    
    if (isset($message)) echo $message;


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    #$Smartbar->add_item([
    #        'title' => 'All',
    #        'link'  => $API->app_nav().'/comments/?show=all',
    #        'active' => ($status=='all'),
    #    ]);
#
    #$Smartbar->add_item([
    #        'title' => 'Pending',
    #        'link'  => $API->app_nav().'/comments/?status=pending',
    #        'active' => ($status=='pending'),
    #    ]);

    $Smartbar->add_item([
                'id'      => 'sf',
                'title'   => 'By Status',
                'icon'    => 'core/o-grid',
                'active'  => true,
                'type'    => 'filter',
                'arg'     => 'status',
                'options' => [
                                [  
                                    'title' => 'All',
                                    'value'  => 'all'
                                ],
                                [  
                                    'title' => 'Live',
                                    'value'  => 'live'
                                ],
                                [  
                                    'title' => $Lang->get('Pending (%d)', $pending_comment_count),
                                    'value'  => 'pending',
                                    'translate' => false,
                                ],
                                [  
                                    'title' => 'Rejected',
                                    'value'  => 'rejected'
                                ],
                                [  
                                    'title' => 'Spam',
                                    'value'  => 'spam'
                                ],
                            ],
                'actions' => [

                        ],
                ]);

    echo $Smartbar->render();



    
    if (PerchUtil::count($comments)) {
	
		echo $Form->form_start('comments', 'bulk-edit inner');



        $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
        $Listing->add_col([
                'title' => '',
                'type'  => 'checkbox',
                'value'     => function($Comment) use ($Form) {
                    return $Form->checkbox('comment-'.$Comment->id(), '1', 0);
                },
            ]);
        $Listing->add_col([
                'title'     => 'Date',
                'value'      => 'commentDateTime',
                'sort'      => 'commentDateTime',
                'edit_link' => 'edit',
                'format'    => [
                                'type' => 'date',
                                'format' => PERCH_DATE_SHORT,
                                'non-breaking' => true,
                                ],
            ]);

        $Listing->add_col([
                'title' => 'Post',
                'sort' => 'postTitle',
                'value' => function($Comment) {
                    return '<span title="'.PerchUtil::html(strip_tags($Comment->commentHTML()), true).'">'.PerchUtil::excerpt_char($Comment->postTitle(), 40, true, false, '...').'</span>';
                },
            ]);

        $Listing->add_col([
                'title' => 'Commenter',
                'sort' => 'commentName',
                'value' => function($Comment) {
                    return '<span title="'.PerchUtil::html($Comment->commentName(), true).'">'.PerchUtil::excerpt_char($Comment->commentName(), 40, true, false, '...').'</span>';
                },
            ]);

        $Listing->add_col([
                'title' => 'Type',
                'value' => function($Comment) use ($Lang) {
                    if ($Comment->webmention()) {
                        return $Lang->get(ucfirst($Comment->webmentionType()));
                    }
                    return $Lang->get('Comment');
                },
                'sort' => 'webmentionType',
            ]);

        $Listing->add_col([
                'title' => 'Email',
                'value' => 'commentEmail',
                'sort' => 'commentEmail',
                'gravatar'  => 'commentEmail',
            ]);
        

        echo $Listing->render($comments);



?>
    <div class="controls" id="comment-controls">
<?php    
		$opts = array();
		$opts[] = array('label'=>'Mark selected as', 'value'=>'', 'disabled'=>true);
		$opts[] = array('label'=>'Live',      'value'=>'LIVE');
		$opts[] = array('label'=>'Spam',      'value'=>'SPAM');
		$opts[] = array('label'=>'Rejected',  'value'=>'REJECTED');
        $opts[] = array('label'=>'Pending',   'value'=>'PENDING');
		$opts[] = array('label'=>'Deleted',   'value'=>'DELETE');
		    		
		//echo ;
        echo $Form->submit_field('btnSubmit', 'Submit', false, 'button button-small action-info', $Form->select('commentStatus', $opts, '', ''));
?>
    </div>
<?php    

    echo $Form->form_end();
    

    } // if pages
    
