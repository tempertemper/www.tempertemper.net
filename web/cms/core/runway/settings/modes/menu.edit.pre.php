<?php
	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(24);

	$MenuItems = new PerchMenuItems;

	$id = PerchUtil::get('id');

	if ($id) {

		$id = (int) $id; 

		$MenuItem = $MenuItems->find($id);
		$details  = $MenuItem->to_array();
	} else {
		$MenuItem = null;
		$details  = [];
	}


	$parentID = PerchUtil::get('pid');

	if (!$parentID) {
		$parentID = (int) $MenuItem->parentID();
	}

	$Section = $MenuItems->find($parentID);


	$all_apps = $Perch->get_apps();
	$apps = [];

	if (PerchUtil::count($all_apps)) {
		foreach($all_apps as $app) {
			if ($app['label']) {
				$apps[] = $app['label'].'|'.$app['id'];	
			}
			
		}
	}

	$apps = implode(',', $apps);


	$collections = '';

	if (PERCH_RUNWAY) {
		$Collections = new PerchContent_Collections;
		$all_collections = $Collections->all();

		$collections = [];

		if (PerchUtil::count($all_collections)) {
			foreach($all_collections as $Collection) {
				$collections[] = $Collection->collectionKey().'|'.$Collection->id();	
			}
		}

		$collections = implode(',', $collections);
	}



	$Privs = new PerchUserPrivileges;
	$all_privs = $Privs->all();

	$privs = [];

	if (PerchUtil::count($all_privs)) {
		foreach($all_privs as $priv) {
			$privs[] = $priv->privTitle().'|'.$priv->id();		
		}
	}

	$privs = implode(',', $privs);

	$default_fields = '<perch:x id="itemTitle" type="text" label="Title" required="true" />
						<perch:x id="app" type="select" options="'.$apps.'" label="Link to App" allowempty="true" />
						<perch:x id="collection" type="select" options="'.$collections.'" label="or Link to Collection" runway="true" allowempty="true" />
						<perch:x id="link" type="text" label="or Link to URL" help="For external URLs be sure to include http:// or https://" />
						<perch:x id="privID" type="select" options="'.$privs.'" label="For users with privilege" allowempty="true" />
						<perch:x id="itemActive" type="checkbox" label="Active" value="1" default="1" />';

	$Template   = $API->get('Template');
	$Template->set_from_string($default_fields, 'x');

	$Form = $API->get('Form');
	$Form->handle_empty_block_generation($Template);
    $Form->set_required_fields_from_template($Template, $details);

    if ($Form->submitted()) {

    	$data = $Form->get_posted_content($Template, $MenuItems, $MenuItem);

    	if (is_object($MenuItem)) {
    		$MenuItem->squirrel('itemID', $id);
    	}

    	$data['parentID'] = $parentID;

    	$dynf = PerchUtil::json_safe_decode($data['itemDynamicFields'], true);

    	if (isset($dynf['app']) && $dynf['app']!='') {
    		$data['itemType'] = 'app';
    		$data['itemValue'] = $dynf['app'];
    	}

    	if (isset($dynf['collection']) && $dynf['collection']!='') {
    		$data['itemType'] = 'app';
    		$data['itemValue'] = $dynf['collection'];
    	}

    	if (isset($dynf['link']) && trim($dynf['link'])!='') {
    		$data['itemType'] = 'link';
    		$data['itemValue'] = $dynf['link'];
    	}

    	if (!isset($data['itemActive'])) {
    		$data['itemActive'] = '0';
    	}

    	unset($data['itemDynamicFields']);

    	PerchUtil::debug($data);

        if (!is_object($MenuItem)) {
            $MenuItem = $MenuItems->create($data);
            PerchUtil::redirect(PERCH_LOGINPATH .'/core/settings/menu/edit/?id='.$MenuItem->id().'&created=1');
        }


        $MenuItem->update($data);
    	


        if (is_object($MenuItem)) {
        	$Alert->set('success', $Lang->get('Your menu item has been successfully edited. Return to %slisting%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/menu/items/?id='.$Section->id().'" class="notification-link">', '</a>'));
        }else{
        	$Alert->set('failure', $Lang->get('Sorry, that menu item could not be edited.'));
        }

        $details  = $MenuItem->to_array();
        
    } 

    if (isset($_GET['created'])) {

    	if ($Section) {
    		$Alert->set('success', $Lang->get('Your menu item has been successfully created. Return to %slisting%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/menu/items/?id='.$Section->id().'" class="notification-link">', '</a>'));	
    	} else {
    		$Alert->set('success', $Lang->get('Your menu item has been successfully created. Return to %slisting%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/menu/" class="notification-link">', '</a>'));
    	}
    	
    }

