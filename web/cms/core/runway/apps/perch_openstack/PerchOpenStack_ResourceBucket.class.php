<?php

include(__DIR__.'/lib/autoload.php');

use \OpenStack\Bootstrap;
use \OpenStack\ObjectStorage;

class PerchOpenStack_ResourceBucket extends PerchResourceBucket
{

	private static $init = false;

	public function __construct($details)
	{
		parent::__construct($details);
		
		$this->file_path = 'swift://'.$details['file_path'];

		if (!self::$init) {

			$config = PerchConfig::get('openstack_object_storage');

			Bootstrap::useStreamWrappers();
			
			Bootstrap::setConfiguration([
				'account'                => (isset($config['account']) 	  ? $config['account']    : null),
				'key'                    => (isset($config['key']) 		  ? $config['key'] 		  : null),
				'username'               => (isset($config['username'])   ? $config['username']   : null),
				'password'               => (isset($config['password'])   ? $config['password']   : null),
				'tenantname'             => (isset($config['tenantname']) ? $config['tenantname'] : null),
				'tenantid'               => (isset($config['tenantid'])   ? $config['tenantid']   : null),
				'endpoint'               => $config['endpoint'],
				'openstack.swift.region' => $config['region'],
			]);

			self::$init = true;
		}


	}

	public function ready_to_write()
	{
		return true;
	}


}