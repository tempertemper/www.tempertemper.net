<?php

class PerchDB {
	
	static private $instance;
	
	static public $driver = '';

	public static function fetch($config=null)
	{	    
        if (!isset(self::$instance)) {

        	if (defined('PDO::MYSQL_ATTR_LOCAL_INFILE')) {
        		$c = 'PerchDB_MySQL';
        		PerchDB::$driver = 'PDO';
        	}else{
        		$c = 'PerchDB_MySQLi';
        		PerchDB::$driver = 'MySQLi';
        	}
            self::$instance = new $c($config);
        }

        return self::$instance;
	}
	
}
