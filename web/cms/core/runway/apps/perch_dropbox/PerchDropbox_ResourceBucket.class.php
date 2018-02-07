<?php
require_once(__DIR__.'/vendor/autoload.php');
require_once(__DIR__.'/DropboxStream.class.php');

use Kunnu\Dropbox\DropboxApp;


class PerchDropbox_ResourceBucket extends PerchResourceBucket
{
	private $Client;

	public function __construct($details)
	{
		parent::__construct($details);
		
		$this->file_path = 'dropbox://'.$details['file_path'];

		$dropbox_config = PerchConfig::get('dropbox');

		if (in_array('dropbox', stream_get_wrappers())) stream_wrapper_unregister('dropbox');
		stream_wrapper_register('dropbox', 'DropboxStream', STREAM_IS_URL);
	}

	public function ready_to_write()
	{
		return true;
	}



}