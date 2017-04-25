<?php
include(__DIR__.'/lib/autoload.php');

use Aws\S3\S3Client;

class PerchS3_ResourceBucket extends PerchResourceBucket
{
	private $Client;

	public function __construct($details)
	{
		parent::__construct($details);

		$this->file_path = 's3://'.$details['file_path'];

		$s3_config = PerchConfig::get('amazon_s3');

		$this->Client = S3Client::factory([
			'credentials' => [
				'key'    => $s3_config['access_key_id'],
		    	'secret' => $s3_config['secret_access_key'],
			],
			'region'  => $details['region'],
			'version' => 'latest',
		]);
		 
		$this->Client->registerStreamWrapper();

	}

	public function ready_to_write()
	{
		return true;
	}


}