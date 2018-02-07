<?php

class PerchResourceBuckets
{

	private static $bucket_list = false;

	public static function get($bucket_name=PERCH_DEFAULT_BUCKET)
	{
		if (trim($bucket_name) == '') $bucket_name = PERCH_DEFAULT_BUCKET;

		// hardwire default, most common case
		$bucket = [
			'name'      => 'default',
			'type'      => 'file',
			'web_path'  => PERCH_RESPATH,
			'file_path' => PERCH_RESFILEPATH,
		];

		if ($bucket_name && $bucket_name!='default') {
		    
		    // try buckets file
		    if (self::$bucket_list===false) {
		    	self::load_bucket_list();
		    }
		                             
		    if (PerchUtil::count(self::$bucket_list) && isset(self::$bucket_list[$bucket_name])) {
		        $bucket = self::$bucket_list[$bucket_name];
		        $bucket['name'] = $bucket_name;
		        return self::factory($bucket);
		    }         

		    // not defined, so treat as a subfolder of resources
		    $bucket['name']      = $bucket_name;
		    $bucket['web_path']  .= '/'.$bucket_name;
		    $bucket['file_path'] = PerchUtil::file_path($bucket['file_path'].'/'.$bucket_name);

		    if (!isset($bucket['type'])) $bucket['type'] = 'file';
		}

		return self::factory($bucket);
	}

	public static function get_all_remote()
	{
		$out = [];

		if (self::$bucket_list===false) {
			self::load_bucket_list();
		}
		                         
		if (PerchUtil::count(self::$bucket_list)) {
			foreach(self::$bucket_list as $name=>$bucket) {
				if (isset($bucket['type']) && $bucket['type']!='file') {
					$bucket['name'] = $name;
					$out[] = self::factory($bucket);
				}
			}
		} 
		return $out;
	}

	public static function get_all_unfiltered($Role)
	{
		$out   = [];
		$found = [$Role->roleSlug()];

		if (self::$bucket_list===false) {
			self::load_bucket_list();
		}
		                         
		if (PerchUtil::count(self::$bucket_list)) {
			foreach(self::$bucket_list as $name=>$bucket) {
				if (!in_array($name, $found)) {
					$bucket['name'] = $name;
					$found[] = $name;
					$out[] = self::factory($bucket);
				}
			}
		} 

		$sql = 'SELECT DISTINCT resourceBucket FROM '.PERCH_DB_PREFIX.'resources WHERE resourceAWOL=0 AND resourceType !=""';
    	$DB = PerchDB::fetch();
    	$result = $DB->get_rows_flat($sql);
        if (is_array($result)) {

        	$Perch = Perch::fetch();

        	foreach($result as $name) {
        		if (!in_array($name, $found)) {
					$out[] = self::factory($Perch->get_resource_bucket($name));
        		}
        	}
        }

		return $out;
	}

	public static function load_bucket_list()
	{
		$bucket_list_file = PerchUtil::file_path(PERCH_PATH.'/config/buckets.php');
		if (file_exists($bucket_list_file)) {
		    self::$bucket_list = include ($bucket_list_file);
		    if (self::$bucket_list==false) self::$bucket_list = [];
		}else{
		    self::$bucket_list = [];
		}
	}

	public static function factory($bucket)
	{
		$type = isset($bucket['type']) ? $bucket['type'] : 'file'; 

		if (!PERCH_RUNWAY || $type=='file') return new PerchResourceBucket($bucket);

		$handlers = PerchSystem::get_registered_bucket_handlers();


		if (isset($handlers[$type])) {

			return new $handlers[$type]($bucket);
		}else{
			$config = PerchConfig::get($type);
			if ($config && is_array($config) && isset($config['handler']) && isset($config['handler_path'])) {
				include $config['handler_path'];
				PerchSystem::register_bucket_handler($type, $config['handler']);
				return new $config['handler']($bucket);
			}else{
				error_log("Handler not found: $type");
			}
		}	
	}
}