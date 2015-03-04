<?php

class PerchConfig
{
	static $config = false;

	public static function get($opt)
	{
		$config = self::get_config();

		if (isset($config[$opt])) return $config[$opt];

		return false;
	}

	public static function get_config()
	{
		if (self::$config!=false) return self::$config;

		self::$config = include PerchUtil::file_path(PERCH_PATH.'/config/runway.php');

		return self::$config;
	}

}
