<?php
    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');


    // test to see if image folder is writable
    $DefaultBucket = PerchResourceBuckets::get();
    $image_folder_writable = $DefaultBucket->ready_to_write();

    $Form = new PerchForm('settings');

    $req = array();
    $req['headerColour']        = "Required";

    $Form->set_required($req);


    if ($Form->posted() && $Form->validate()) {
    	$postvars = array('headerColour', 'headerScheme', 'lang', 'hideBranding', 'helpURL', 'siteURL', 'dashboard', 'sidebar_back_link', 'hide_pwd_reset', 'keyboardShortcuts');
    	$checkboxes = array('hideBranding', 'dashboard', 'sidebar_back_link', 'hide_pwd_reset', 'keyboardShortcuts');

        if (PERCH_RUNWAY) {
            $postvars[]   = 'siteOffline';
            $checkboxes[] = 'siteOffline';
        }

    	include('_app_settings.pre.php');

    	$data = $Form->receive($postvars);

    	foreach($checkboxes as $checkbox) {
    	    if (!isset($data[$checkbox])) $data[$checkbox] = '0';
    	}

        if (isset($_POST['logo_remove']) && $_POST['logo_remove']=='1') {
            $data['logoPath'] = '';
        }

    	foreach($data as $key=>$value) {
    	    $Settings->set($key, $value);
    	}



        $Lang = PerchLang::fetch();
        $Lang->reload();

    	$Alert->set('success', PerchLang::get("Your settings have been updated."));

    	// image upload
    	if (isset($_FILES['customlogo']) &&  (int) $_FILES['customlogo']['size'] > 0) {

            // new
            $FieldTag = new PerchXMLTag('<perch:content id="customlogo" type="image" disable-asset-panel="true" detect-type="true" accept="webimage" />');
            $FieldTag->set('input_id', 'customlogo');

            $Assets    = new PerchAssets_Assets;
            $Resources = new PerchResources;

            $FieldType = PerchFieldTypes::get($FieldTag->type(), $Form, $FieldTag, false, 'system');
            $var       = $FieldType->get_raw();

            if (PerchUtil::count($var)) {

                $ids     = $Resources->get_logged_ids();
                $assetID = $ids[0];
                $Asset   = $Assets->find($assetID);
                $Asset->reindex();
                $Asset->mark_as_library();

                $Settings->set('logoPath', $Asset->web_path());
            }
    	}

        $Settings->reload();
    }



    //PerchUtil::debug('Image folder writable? ' . $image_folder_writable);



    $details = $Settings->get_as_array();
