<?php

class PerchAssets_Importer
{

	public function add_item($data)
	{
		if (!count($data)) return;

		$defaults = [
			'type'   => 'image',
			'bucket' => 'default',
		];

		$data = PerchUtil::extend($defaults, $data);

		$Perch  = PerchAdmin::fetch();

		$Bucket  = PerchResourceBuckets::get($data['bucket']);

		$Bucket->initialise();

		// As this is an import.
		$Bucket->enable_non_uploaded_files();

		if ($Bucket->ready_to_write()) {

			$Assets = new PerchAssets_Assets;

			$store = [];

			$file_path = $data['path'];
			$file_name = basename($file_path);

			$AssetMeta = $Assets->get_meta_data($file_path, $file_name);

			if ($data['type'] == 'image') {
				$PerchImage = new PerchImage;
                $PerchImage->orientate_image($file_path);
			}


			$result   = $Bucket->write_file($file_path, $file_name);	

			$target   = $result['path'];
	        $filename = $result['name'];
	        $filesize = filesize($file_path);

	        $store['_default'] = rtrim($Bucket->get_web_path(), '/').'/'.$filename;

	        $store['path']   = $filename;
            $store['size']   = $filesize ?: filesize($target);
            $store['bucket'] = $Bucket->get_name();

            // Is this an SVG?
            $svg = false;

            $size = getimagesize($target);
            if (PerchUtil::count($size)) {
                $store['w'] = $size[0];
                $store['h'] = $size[1];
                if (isset($size['mime'])) $store['mime'] = $size['mime'];
            }else{
                $PerchImage = new PerchImage;

                if ($PerchImage->is_webp($target)) {

                    $store['mime'] = 'image/webp';

                }elseif ($PerchImage->is_svg($target)) {
                    $svg = true;
                    $size = $PerchImage->get_svg_size($target);
                    if (PerchUtil::count($size)) {
                        $store['w'] = $size['w'];
                        $store['h'] = $size['h'];
                        if (isset($size['mime'])) $store['mime'] = $size['mime'];
                    }
                }else{
                    $store['mime'] = PerchUtil::get_mime_type($target);
                }
            }

             // thumbnail
            if ($data['type']=='image') {

                $PerchImage = new PerchImage;
                $PerchImage->set_density(2);

                $result = false;

                if (!$result) $result = $PerchImage->resize_image($target, 150, 150, false, 'thumb');
                if (is_array($result)) {
                    //PerchUtil::debug($result, 'notice');
                    if (!isset($store['sizes'])) $store['sizes'] = array();

                    $variant_key = 'thumb';
                    $tmp = array();
                    $tmp['w']        = $result['w'];
                    $tmp['h']        = $result['h'];
                    $tmp['target_w'] = 150;
                    $tmp['target_h'] = 150;
                    $tmp['density']  = 2;
                    $tmp['path']     = $result['file_name'];
                    $tmp['size']     = filesize($result['file_path']);
                    $tmp['mime']     = (isset($result['mime']) ? $result['mime'] : $store['mime']);

                    if (is_array($result) && isset($result['_resourceID'])) {
                        $tmp['assetID'] = $result['_resourceID'];
                    }

                    $store['sizes'][$variant_key] = $tmp;
                }
                unset($result);
                unset($PerchImage);
            }
            if ($data['type']=='file') {
                $PerchImage = new PerchImage;
                $PerchImage->set_density(2);

                $result = $PerchImage->thumbnail_file($target, 150, 150, false);
                if (is_array($result)) {
                    if (!isset($store['sizes'])) $store['sizes'] = array();

                    $variant_key = 'thumb';
                    $tmp = array();
                    $tmp['w']        = $result['w'];
                    $tmp['h']        = $result['h'];
                    $tmp['target_w'] = 150;
                    $tmp['target_h'] = 150;
                    $tmp['density']  = 2;
                    $tmp['path']     = $result['file_name'];
                    $tmp['size']     = filesize($result['file_path']);
                    $tmp['mime']     = (isset($result['mime']) ? $result['mime'] : '');

                    if (is_array($result) && isset($result['_resourceID'])) {
                        $tmp['assetID'] = $result['_resourceID'];
                    }

                    $store['sizes'][$variant_key] = $tmp;
                }
                unset($result);
                unset($PerchImage);
            }

            $Resources = new PerchResources;
            $parentID = $Resources->log($this->app_id, $store['bucket'], $store['path'], 0, 'orig', false, $store, $AssetMeta);

             // variants
            if (isset($store['sizes']) && PerchUtil::count($store['sizes'])) {
                foreach($store['sizes'] as $key=>$size) {
                    $Resources->log($this->app_id, $store['bucket'], $size['path'], $parentID, $key, false, $size, $AssetMeta);
                }
            }

            $store['id'] = $parentID;

            return $store;
		}

	}

}