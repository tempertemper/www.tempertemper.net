<?php
    header('Content-Type: application/json');
    $FieldTag = new PerchXMLTag('<perch:content id="file" type="image" disable-asset-panel="true" detect-type="true" accept="all" />');
    $FieldTag->set('input_id', 'file');

    if (isset($_POST['bucket']) && !empty($_POST['bucket'])) {
        $bucket = $_POST['bucket'];
        if ($bucket == 'null') $bucket = null;
        
        $FieldTag->set('bucket', $bucket);
    }

    if (!$CurrentUser->has_priv('assets.create')) {
        die();
    }

    $Assets  = new PerchAssets_Assets;

    $message = false;
    
    $assetID = false;
    $Asset = false;
           
    $Form = new PerchForm('edit');
    
    if (PerchUtil::count($_FILES)) {
    
        //PerchUtil::debug($_FILES);

        $Resources = new PerchResources;

		$data = array();
        $FieldType = PerchFieldTypes::get($FieldTag->type(), $Form, $FieldTag, null, 'assets');
        $var       = $FieldType->get_raw();

        if (PerchUtil::count($var)) {
            
            $ids     = $Resources->get_logged_ids();
            $Asset   = $Assets->find_original($ids);
            $assetID = $Asset->id();
            $Asset->reindex();

            //PerchUtil::debug($ids);

            if (PerchUtil::count($ids)) {

                if (!PerchSession::is_set('resourceIDs')) {
                    $logged_ids = array();
                    PerchSession::set('resourceIDs', $logged_ids);
                }else{
                    $logged_ids = PerchSession::get('resourceIDs');
                }

                foreach($ids as $assetID) {
                    if (!in_array($assetID, $logged_ids)) {
                        $logged_ids[] = $assetID;
                    }
                }
                PerchSession::set('resourceIDs', $logged_ids);
            }

            $type = $Asset->get_type();
            if ($type=='image') $type = 'img';
            $out = $Asset->to_api_array();
            echo json_encode(array_merge($out, array('type'=>$type, 'debug'=>$ids)));
            
        }
   		$Alert->set('success', PerchLang::get('Successfully updated'));
    }
  	
