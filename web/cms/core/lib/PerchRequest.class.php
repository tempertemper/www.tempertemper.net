<?php

class PerchRequest
{
	private static $get;
	private static $post;
	private static $env;
	private static $url;
	private static $cookie;

	public static function init($get, $post, $server, $cookie)
	{
		self::$get    = $get;   
		self::$post   = $post;  
		self::$env    = $server; 
		self::$cookie = $cookie;

		self::$url  = new PerchURL(self::$env, self::$get);
	}

	public static function get($var, $default=false)
	{
		if (isset(self::$get[$var]) && self::$get[$var]) {
			return urldecode(self::$get[$var]);
		}
		return $default;
	}

	public static function post($var = false, $default = false)
	{
		if ($var === false) {
			return self::$post;
		}

		if (isset(self::$post[$var]) && self::$post[$var]) {
			return self::$post[$var];
		}
		return $default;
	}

	public static function env($var, $default=false)
	{
		if (isset(self::$env[$var]) && self::$env[$var]) {
			return self::$env[$var];
		}
		return $default;
	}

	public static function cookie($var, $default=false)
	{
		if (isset(self::$cookie[$var]) && self::$cookie[$var]) {
			return self::$cookie[$var];
		}
		return $default;
	}

	public static function url()
	{
		return self::$url;
	}

	public static function overwrite($type, $key, $value)
	{
		switch($type) {
			case 'get':
				self::$get[$key] = $value;
				break;

			case 'post':
				self::$post[$key] = $value;
				break;
		}
	}

	public static function reset_post()
	{
		self::$post = [];
	}
}