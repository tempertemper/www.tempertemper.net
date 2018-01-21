<?php
	header('Content-Type: application/javascript');
	include(__DIR__ . '/../inc/pre_config.php');
	include(__DIR__ . '/../../config/config.php');
	include(PERCH_CORE . '/inc/loader.php');
	$Perch  = PerchAdmin::fetch();
	$strings = array(
		'Save',
		'Undo',
		'Image title',
		'File title',
		'File to upload',
		'Style',
		'Upload',
		'or',
		'Cancel',
		'New',
		'Mixed',
		'Toggle sidebar',
		'Delete this item?',
		'Add Another',
		'Select an existing image',
		'Select an existing file',
		'Select or upload an image',
		'Select or upload a file',
		'Select an Asset',
		'Add Asset',
		'Use Selected',
		'Drop files here or click to upload',
		'Close',
		'No matching assets found',
		'Clear Filter',
		'Name',
		'Type',
		'Dimensions',
		'Size',
		'Add a tag',
		'Are you sure?',
		'Yes',
		'No',
		'Add an item',
		'Bucket',
		'Uploading',
		'%s is also editing here'
	);
	echo "Perch.Lang.init({\n\t";

	foreach($strings as $string) {
		echo "'{$string}':'".addslashes(PerchLang::get($string))."',\n\t";
	}
	echo "'EOF':'EOF'\n";
	echo '});';
