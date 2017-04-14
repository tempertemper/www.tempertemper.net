<?php

	$Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Name',
            'value'     => 'resourceTitle',
            'sort'      => 'resourceTitle',
            'edit_link' => 'edit',
            'icon'      => function($item) {
            	return PerchUI::icon($item->icon_for_type(), 16, $item->display_mime(), 'asset-icon').' ';
            },
        ]);
    $Listing->add_col([
            'title'     => 'Type',
            'value'     => function($item){
				return PerchUtil::html($item->display_mime());
            },
            'sort'      => 'resourceType',
        ]);
   	$Listing->add_col([
            'title'     => 'Dimensions',
            'value'     => function($item){
				$type = $item->get_type();
				if ($type=='image') {
					return PerchUtil::html($item->display_width() . ' × '. $item->display_height());
				}else{
					return '-';
				} 
            },
            'sort'      => 'resourceWidth',
        ]);
   	$Listing->add_col([
            'title'     => 'Size',
            'value'     => function($item){
				return $item->file_size();
            },
            'sort'      => 'resourceFileSize',
        ]);
    $Listing->add_delete_action([
            'priv'   => 'assets.delete',
            'inline' => true,
            'path'   => 'delete',
        ]);

    echo $Listing->render($assets);

