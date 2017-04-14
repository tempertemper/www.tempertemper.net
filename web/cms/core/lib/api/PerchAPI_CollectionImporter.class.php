<?php

class PerchAPI_CollectionImporter extends PerchCollectionImporter
{
    public $app_id = false;
    public $version = 1.0;
    
    private $Lang = false;
    
    function __construct($version=1.0, $app_id, $Lang)
    {
        $this->app_id = $app_id;
        $this->version = $version;
        
        $this->Lang = $Lang;
    }
   
}
