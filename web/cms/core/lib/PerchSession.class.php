<?php

class PerchSession
{
	public static function commence()
	{
		if (!defined('PERCH_PARANOID')) {
			define('PERCH_PARANOID', false);
		}

	    if (!isset($_SESSION['ready'])) {

	    	$path          = '/';
			$domain        = null;
			$secure        = (defined('PERCH_SSL') && PERCH_SSL);
			$http_only     = true;

	    	if (PERCH_PARANOID) {
	    		if (!defined('PERCH_SESSION_TIMEOUT_MINS')) {
					define('PERCH_SESSION_TIMEOUT_MINS', 20);
	    		}
	    		session_set_cookie_params((PERCH_SESSION_TIMEOUT_MINS*60), $path, $domain, $secure, $http_only);
	    	} else {
	    		session_set_cookie_params(0, $path, $domain, $secure, $http_only);
	    	}

	        session_start();
	        $_SESSION['ready'] = true;
	        self::extend_session();
	    }
	}

	public static function regenerate()
	{
		self::commence();
		session_regenerate_id(true);
	}

	public static function extend_session()
	{
		self::commence();

		if (!PERCH_PARANOID) return;

		$path          = '/';
		$domain        = null;
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
	    self::extend_session();
	    session_write_close();
	}

	public static function get_all()
	{
		self::commence();
        return $_SESSION;
	}
}