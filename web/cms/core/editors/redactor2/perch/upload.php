<?php
    # include the API
    include('../../../../../core/inc/api.php');

    # include Assets
    if (!class_exists('PerchAssets_Assets', false)) {
        include_once(PERCH_CORE.'/apps/assets/PerchAssets_Assets.class.php');
        include_once(PERCH_CORE.'/apps/assets/PerchAssets_Asset.class.php');
    }


    /* -------- SET UP TEMPLATE TAG ---------- */

    $Tag = new PerchXMLTag('<perch:content id="file" disable-asset-panel="true" detect-type="true" />');
    $Tag->set('input_id', 'file');

    $API = new PerchAPI(1.0, 'redactorjs');

    if (isset($_GET['filetype']) && $_GET['filetype'] == 'image'){
        $is_image = true;
        $Tag->set('type', 'image');
    }else{
        $is_image = false;
        $Tag->set('type', 'file');
    }


    /* -------- GET THE RESOURCE BUCKET TO USE ---------- */
    $bucket_name  = 'default';
    if (isset($_POST['bucket']) && $_POST['bucket']!='') $bucket_name = $_POST['bucket'];
    $Tag->set('bucket', $bucket_name);

    $Bucket = PerchResourceBuckets::get($bucket_name);
    $Bucket->initialise();

    if ($is_image) {
        $width = 800;
        if (isset($_POST['width']) && $_POST['width'] != '') $width = (int)$_POST['width'];
        $Tag->set('width', $width);

        $height = false;
        if (isset($_POST['height']) && $_POST['height'] != '') $height = (int)$_POST['height'];
        $Tag->set('height', $height);

        $crop = false;
        if (isset($_POST['crop']) && $_POST['crop']=='true') $crop = true;
        $Tag->set('crop', $crop);

        $quality = false;
        if (isset($_POST['quality']) && $_POST['quality'] != '') $quality = (int)$_POST['quality'];
        if ($quality) $Tag->set('quality', $quality);

        $sharpen = false;
        if (isset($_POST['sharpen']) && $_POST['sharpen'] != '') $sharpen = (int)$_POST['sharpen'];
        if ($sharpen) $Tag->set('sharpen', $sharpen);

        $density = 1;
        if (isset($_POST['density']) && $_POST['density'] != '') $density = (float)$_POST['density'];
        if ($density) $Tag->set('density', $density);

    }

    $Assets  = new PerchAssets_Assets;

    $message = false;
    $assetID = false;
    $Asset   = false;

    $Form      = new PerchForm('edit');
    $Resources = new PerchResources;

    $data = array();
    $FieldType = PerchFieldTypes::get($Tag->type(), $Form, $Tag, array($Tag));
    $var       = $FieldType->get_raw();

    

    if (PerchUtil::count($var)) {

        $ids     = $Resources->get_logged_ids();
        $Resources->mark_group_as_library($ids);
        $assetID = $ids[0];
        $Asset   = $Assets->find($assetID);

        $Asset->reindex();

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


        if ($is_image) {
            $result = $Assets->get_resize_profile($Asset->id(), $width, $height, ($crop?'1':'0'), false, $density);

            if ($result) {
                echo stripslashes(PerchUtil::json_safe_encode(array(
                        'url' => $result['web_path'], 
                        'id'  => $assetID,
                    )));
            }else{
                echo stripslashes(PerchUtil::json_safe_encode(array(
                        'url' => $Asset->web_path(),
                        'id'  => $assetID,
                    )));
            }
            exit;
        }else{
            echo stripslashes(PerchUtil::json_safe_encode(array(
                    'url' => $Asset->web_path(),
                    'id'  => $assetID,
                )));
            exit;
        }


    }

    echo 'FAIL';


