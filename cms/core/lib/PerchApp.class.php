<?php

class PerchApp extends PerchFactory
{
    static protected $instance;
    
    protected $table;       // DB table name e.g. 'tblContentText
	protected $pk;          // primary key e.g. 'textID';
	
	protected $cache  = false;
	
    public function clear_cache()
    {
        $this->cache = false;
    }
    
}