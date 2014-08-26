<?php

class PerchSession 
{
	
	public static function commence()
	{
	    if (!isset($_SESSION['ready'])) {
	        session_start();
	        $_SESSION['ready'] = true;
	    }
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
	    session_regenerate_id(false);
	    session_write_close();
	}
}
?>