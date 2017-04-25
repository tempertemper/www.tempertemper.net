<?php
	return [

		/*
		|--------------------------------------------------------------------------
		| Environment settings
		|--------------------------------------------------------------------------
		|
		| Various file operations (notably moving files to cloud storage) needs a 
		| temporary folder to work in. This should be OUTSIDE of the
		| web root, and should be writable by PHP.
		|
		*/

		'env' => [

			'temp_folder' => '/tmp',

		],

		/*
		|--------------------------------------------------------------------------
		| Routing Tokens
		|--------------------------------------------------------------------------
		|
		| URL Routing uses tokens for pattern matching. Custom tokens can be 
		| specified as 'token_id' => 'regular expression'.
		| For performance, tokens are converted to regexp at edit time. If you 
		| make a change here, re-save the page options for the change to take effect.
		|
		*/
	
		'routing_tokens' => [

		],		

		/*
		|--------------------------------------------------------------------------
		| Dropbox
		|--------------------------------------------------------------------------
		|
		| Access token for accessing a Dropbox account
		|
		*/
	
		'dropbox' => [

		    'access_token' => '',
		    'handler'      => 'PerchDropbox_ResourceBucket',
		    'handler_path' => PERCH_CORE.'/runway/apps/perch_dropbox/PerchDropbox_ResourceBucket.class.php',

		],		

		/*
		|--------------------------------------------------------------------------
		| Amazon S3
		|--------------------------------------------------------------------------
		|
		| Amazon security credentials for accessing S3 
		|
		*/
	
		'amazon_s3' => [

			'access_key_id'     => '',
			'secret_access_key' => '',
			'handler'			=> 'PerchS3_ResourceBucket',
			'handler_path'   	=> PERCH_CORE.'/runway/apps/perch_s3/PerchS3_ResourceBucket.class.php',

		],

		/*
		|--------------------------------------------------------------------------
		| OpenStack Object Storage 
		|--------------------------------------------------------------------------
		|
		| Security credentials for accessing OpenStack Object Storage services such 
		| as Rackspace Cloud Files.
		|
		*/
	
		'openstack_object_storage' => [

			'username'     => '',
			'password'     => '',
			'tenantid'     => '',
			'endpoint'     => 'https://lon.identity.api.rackspacecloud.com/v2.0',
			'region'       => 'LON',
			'handler'      => 'PerchOpenStack_ResourceBucket',
			'handler_path' => PERCH_CORE.'/runway/apps/perch_openstack/PerchOpenStack_ResourceBucket.class.php',

		],

		/*
		|--------------------------------------------------------------------------
		| Varnish
		|--------------------------------------------------------------------------
		|
		| Enable support for purging and banning pages from your varnish cache
		|
		*/
		
		'varnish' => [

			'enabled' => false,

		],

		/*
		|--------------------------------------------------------------------------
		| Search
		|--------------------------------------------------------------------------
		|
		| Configure your search provider and any settings needed for it.
		|
		*/
		
		'search' => [

			'provider' => 'native',

		],

		/*
		|--------------------------------------------------------------------------
		| Cache
		|--------------------------------------------------------------------------
		|
		| Configure your caching provider and any settings needed for it.
		|
		*/
		
		'cache' => [

			'provider' => 'native',

		],
		

	];