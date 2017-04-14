<?php
	$API    = new PerchAPI(1.0, 'core');
	$Lang   = $API->get('Lang');
	$HTML   = $API->get('HTML');
	$Paging = $API->get('Paging');

	$Paging->set_per_page(24);

	$MenuItems = new PerchMenuItems;

	//PerchUtil::hold_redirects();

	$id = PerchUtil::get('id');

	if ($id) {

		$id = (int) $id; 

		$MenuItem = $MenuItems->find($id);
		$details  = $MenuItem->to_array();
	} else {
		$MenuItem = null;
		$details  = [];
	}


	$parentID = 0;

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

    	$data['itemType'] = 'menu';

    	$data['parentID'] = $parentID;

    	if (!isset($data['itemActive'])) {
    		$data['itemActive'] = '0';
    	}

    	unset($data['itemDynamicFields']);

    	PerchUtil::debug($data);

        if (!is_object($MenuItem)) {
            $MenuItem = $MenuItems->create($data);
            PerchUtil::redirect(PERCH_LOGINPATH .'/core/settings/menu/section/edit/?id='.$MenuItem->id().'&created=1');
        }


        $MenuItem->update($data);
    	


        if (is_object($MenuItem)) {
        	$Alert->set('success', $Lang->get('Your menu item has been successfully edited. Return to %slisting%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/menu/items/?id='.$MenuItem->id().'" class="notification-link">', '</a>'));
        }else{
        	$Alert->set('failure', $Lang->get('Sorry, that menu item could not be edited.'));
        }

        $details  = $MenuItem->to_array();
        
    } 

    if (isset($_GET['created'])) {
    	$Alert->set('success', $Lang->get('Your menu item has been successfully created. Return to %slisting%s', '<a href="'.PERCH_LOGINPATH .'/core/settings/menu/items/?id='.$MenuItem->id().'" class="notification-link">', '</a>'));
    }

