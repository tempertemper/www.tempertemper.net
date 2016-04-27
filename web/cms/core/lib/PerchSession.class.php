<?php

class PerchSession
{
	public static function commence()
	{
	    if (!isset($_SESSION['ready'])) {

	    	if (!defined('PERCH_SESSION_TIMEOUT_MINS')) {
	    		define('PERCH_SESSION_TIMEOUT_MINS', 20);
	    	}

			$path          = '/';
			$domain        = '';
			$secure        = (defined('PERCH_SSL') && PERCH_SSL);
			$http_only     = true;

	    	session_set_cookie_params((PERCH_SESSION_TIMEOUT_MINS*60), $path, $domain, $secure, $http_only);
	        session_start();
	        self::extend_session();
	        $_SESSION['ready'] = true;
	    }
	}

	public static function regenerate()
	{
		self::commence();
		session_regenerate_id(true);
	}

	public static function extend_session()
	{
		$path          = '/';
		$domain        = '';
		$secure        = (defined('PERCH_SSL') && PERCH_SSL);
		$http_only     = true;

		setcookie(session_name(),session_id(),time()+(PERCH_SESSION_TIMEOUT_MINS*60), $path, $domain, $secure, $http_only);
	}

	public static function set($key, $value)
	{
	    self::commence();
	    $_SESSION[$key] = $value;
	}

	public static function get($key)
	{
	    self::commence();
        if (isset($_SESSION[$key])){
            return $_SESSION[$key];
        }

	    return false;
	}

	public static function is_set($key)
	{
	    self::commence();
        if (isset($_SESSION[$key])) {
            return true;
        }

	    return false;
	}

	public static function delete($key)
	{
	    self::commence();
	    unset($_SESSION[$key]);
	}

	public static function close()
	{
	    if (isset($_SESSION['ready'])) {
            session_write_close();
        }
	}

	public static function keep_alive()
	{
	    self::commence();
	    session_write_close();
	}
}