<?php
    
    $FieldTag = new PerchXMLTag('<perch:content id="image" type="image" disable-asset-panel="true" app-mode="true" detect-type="true" accept="all" title="'.PerchLang::get('Select a file').'" />');
    $FieldTag->set('input_id', 'image');

    $API  = new PerchAPI(1.0, 'assets');
    $HTML = $API->get('HTML');
    $Lang = $API->get('Lang');

    $Assets  = new PerchAssets_Assets;
    $Tags    = new PerchAssets_Tags;

    $Form = new PerchForm('edit');
	
    $message = false;
        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $assetID = (int) $_GET['id'];    
        $Asset = $Assets->find($assetID);

        if ($Asset) {
            if (!$Asset->is_image()) {
                $FieldTag->set('type', 'file');
            }
        }

    }else{

        if (!$CurrentUser->has_priv('assets.create')) {
            PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/assets/');
        }

        $assetID = false;
        $Asset = false;
    }
        
    $Form = new PerchForm('edit');

    $req = array();
    $req['resourceTitle']   = "Required";

    $Form->set_required($req);
    
    if ($Form->posted() && $Form->validate()) {

        /*
        if (isset($_POST['image_remove']) && $_POST['image_remove']=='1') {
            $Asset->delete();
            PerchUtil::redirect()
        }
        */
    
        $created = false;

		$postvars = array('resourceTitle', 'resourceInLibrary', 'resourceBucket');
		$data = $Form->receive($postvars);

        if (isset($data['resourceBucket'])) {
            $FieldTag->set('bucket', $data['resourceBucket']);
        }

        if (!isset($data['resourceInLibrary'])) $data['resourceInLibrary'] = 0;

        $FieldType = PerchFieldTypes::get($FieldTag->type(), $Form, $FieldTag);

        $var       = $FieldType->get_raw();

        if (PerchUtil::count($var)) {
            $Resources = new PerchResources;
            $ids       = $Resources->get_logged_ids();
            $assetID   = array_shift($ids);
            $Asset     = $Assets->find((int)$assetID);
            $created   = true;
        }


        if ($Asset) {

            if ($data['resourceInLibrary']=='1') {
                $Asset->mark_as_library();
            }

            $Asset->update($data);
    		$Asset->reindex();

            // Tags
            if (isset($_POST['tags']) && trim($_POST['tags'])!='') {
                $tag_string = trim($_POST['tags']);
                $Tags->assign_tag_string($Asset->id(), $tag_string, true);
            }
            
            if ($created) {
                //PerchUtil::redirect(PERCH_LOGINPATH.'/core/apps/assets/edit/?id='.$Asset->id().'&created=1');
            }

    		$Alert->set('success', PerchLang::get('Successfully updated'));
        }

    }

    if (isset($_GET['created'])){
        $Alert->set('success', PerchLang::get('Successfully created'));
    }
    
    if ($Asset) {
        $details = $Asset->to_array();    
    }else{
        $details = new ArrayObject(); 
    }
  	