<?php

		if ($Page->pagePath()=='*') {
			$heading = sprintf(PerchLang::get('Editing Shared Regions'));
		}else{
			$heading = sprintf(PerchLang::get('Editing %s Page'),' &#8216;' . PerchUtil::html($Page->pageNavText()) . '&#8217; ');
		}

		echo $HTML->title_panel([
			'heading' => $heading,
			'button'  => [
		                'text' => $Lang->get('Add subpage'),
		                'link' => '/core/apps/content/page/add/?pid='.$Page->id(),
		                'icon' => 'core/plus',
		                'priv' => 'content.pages.create',
		            ]
		], $CurrentUser);

		$Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);
		$Smartbar->add_item([
				'active' => true,
				'title'  => 'Regions',
				'link'   => '/core/apps/content/page/?id='.$Page->id(),
				'icon'   => 'core/o-grid',
			]);

		if ($Page->pagePath()!='*') {
			$Smartbar->add_item([
				'title'  => 'Details',
				'link'   => '/core/apps/content/page/details/?id='.$Page->id(),
				'priv'   => 'content.pages.attributes',
				'icon'   => 'core/o-toggles'
			]);	

			$Smartbar->add_item([
                'title'  => 'Location',
                'link'   => '/core/apps/content/page/url/?id='.$Page->id(),
                'priv'   => 'content.pages.manage_urls',
                'icon'   => 'core/o-signs',
            ]); 


			$Smartbar->add_item([
				'title'    => 'View Page',
				'link'     => rtrim($Settings->get('siteURL')->val(), '/').$Page->pagePath(),
				'icon'     => 'core/document',
				'position' => 'end',
				'new-tab'  => true,
				'link-absolute' => true,
				]);	

			$Smartbar->add_item([
				'title'    => 'Settings',
				'link'     => '/core/apps/content/page/edit/?id='.$Page->id(),
				'priv'     => 'content.pages.edit',
				'icon'     => 'core/gear',
				'position' => 'end',
				]);	 			
		}
	   

		echo $Smartbar->render();

		

	   	$Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);

	   	$Listing->add_filter(function($item) use ($CurrentUser, $Settings) {
	   		return $item->role_may_view($CurrentUser, $Settings);
	   	});

		$Listing->add_col([
		    'title'     => 'Region',
		    'value'     => 'regionKey',
		    'sort'     => 'regionKey',
		    'edit_link' => function($item) use ($CurrentUser) {
		    	if ($item->role_may_edit($CurrentUser)) {
		    		return '../edit';
		    	}
		    	return null;
		    },
		    'icon'      => function($item, $HTML, $Lang) {
				if ($item->has_draft()) {
					return PerchUI::icon('core/o-pencil', 12, $Lang->get('This item is a draft.'), 'icon-draft').' ';
				}
		    }
		]);
		$Listing->add_col([
		    'title'     => 'Type',
		    'sort'		=> 'regionTemplate',
		    'value'     => function($item, $HTML, $Lang) use ($Regions) {
		    	$s = '';

		    	if ($item->regionNew()) {
		    		$s .= $HTML->wrap('span.new', $Lang->get('New'));
		    	}

		    	$s .= $HTML->encode($Regions->template_display_name($item->regionTemplate(), false));

		    	return $s;
		    },
		]);
		$Listing->add_col([
		    'title'     => 'Items',
		    'value'     => function($item, $HTML){
		    	return $item->get_item_count();
		    }
		]);
		if (PERCH_RUNWAY) {
			$Listing->add_col([
			    'title'     => 'Last updated',
			    'sort'		=> 'regionUpdated',
			    'value'     => function($item, $HTML) {
			    	if ($item->regionUpdated()!='0000-00-00 00:00:00') {
						return strftime(PERCH_DATE_SHORT .' '.PERCH_TIME_SHORT, strtotime($item->regionUpdated()));
					}else{
						return '&dash;';
					}
			    },
			    //'class' => 'format-datetime',
			]);
		}

		$Listing->add_misc_action([
				'title' 	=> 'Preview',
				'display'	=> function($item) { return ($item->has_draft() && $item->regionPage() != '*'); },
				'class'     => 'warning',
				'path'		=> function($item, $HTML) use ($Settings) {
					$path = rtrim($Settings->get('siteURL')->val(), '/');
		            return $path.$item->regionPage().'?'.PERCH_PREVIEW_ARG.'=all';
				},
				'new-tab'  => true,
			]);

		$Listing->add_delete_action([
            'priv'   => 'content.regions.delete',
            'inline' => true,
            'path'   => '../delete',
            'display' => function() use ($CurrentUser, $Page) {
            	if ($CurrentUser->has_priv('content.regions.delete') || ($CurrentUser->has_priv('content.pages.delete.own') && $Page->pageCreatorID()==$CurrentUser->id())) {
            		return true;
            	}
            	return false;
            }
        ]);

		$header_row = $Listing->template_heading_row();
		$data_rows 	= $Listing->template_data_rows($regions);


if (PERCH_RUNWAY) {

		$CollectionListing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);

		$CollectionListing->add_filter(function($item) use ($CurrentUser, $Settings) {
	   		return $item->role_may_view($CurrentUser, $Settings);
	   	});

		$CollectionListing->add_col([
		    'title'     => 'Region',
		    'value'     => 'collectionKey',
		    'edit_link' => function($item) use ($CurrentUser) {
		    	if ($item->role_may_edit($CurrentUser)) {
		    		return '../collections';
		    	}
		    	return null;
		    }
		]);
		$CollectionListing->add_col([
		    'title'     => 'Type',
		    'value'     => function($item, $HTML) use ($Regions) {
		    	return $HTML->encode($Regions->template_display_name($item->collectionTemplate(), false));
		    },
		]);
		$CollectionListing->add_col([
		    'title'     => 'Items',
		    'value'     => function($item){
		    	return $item->get_item_count();
		    }
		]);
		
		$CollectionListing->add_col([
		    'title'     => 'Last updated',
		    'value'     => function($item, $HTML) {
		    	if ($item->collectionUpdated()!='0000-00-00 00:00:00') {
					return strftime(PERCH_DATE_SHORT .' '.PERCH_TIME_SHORT, strtotime($item->collectionUpdated()));
				}else{
					return '&dash;';
				}
		    },
		    //'class' => 'format-datetime',
		]);
	

		$CollectionListing->add_misc_action([
				'title' 	=> 'Preview',
				'display'	=> false,
				'class'     => 'warning',
				'path'		=> false,
			]);

		$CollectionListing->add_delete_action([
            'priv'   => 'content.regions.delete',
            'inline' => true,
            'path'   => 'delete',
            'display' => false,
        ]);

        $data_rows .= $CollectionListing->template_data_rows($collections);

} // Collection listing 

		echo $Listing->template($header_row, $data_rows);


        if (isset($created) && $created!==false) {
            echo '<img src="'.PerchUtil::html($Page->pagePath()).'" width="1" height="1" class="off-screen">';
        }
