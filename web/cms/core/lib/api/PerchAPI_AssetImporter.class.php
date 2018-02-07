<?php

class PerchAPI_AssetImporter extends PerchAssets_Importer
{
    public $app_id = false;
    public $version = 1.0;
    
    private $Lang = false;
    
    function __construct($version=1.0, $app_id, $Lang)
    {
        $this->app_id = $app_id;
        $this->version = $version;
        
        $this->Lang = $Lang;

        if (!PERCH_RUNWAY) exit;
    }
   
}
