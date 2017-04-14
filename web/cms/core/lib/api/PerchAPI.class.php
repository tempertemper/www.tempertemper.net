<?php

class PerchAPI
{
    public $app_id = false;
    public $nav_app_id = false;
    public $version = 1.0;
    
    private $Lang = false;
    
    function __construct($version=1.0, $app_id)
    {
        $this->app_id     = $app_id;
        $this->nav_app_id = $app_id;
        $this->version    = $version;
        
        if (!defined('PERCH_APPS_EDITOR_PLUGIN')) define('PERCH_APPS_EDITOR_PLUGIN', 'markitup');
        if (!defined('PERCH_APPS_EDITOR_MARKUP_LANGUAGE')) define('PERCH_APPS_EDITOR_MARKUP_LANGUAGE', 'markdown');

        if (strpos($app_id, '_')===false) {
            $this->Lang = PerchLang::fetch();
        }
    }

    public function get($class)
    {
        $full_class_name = 'PerchAPI_'.$class;
        
        switch ($class) {
            case 'DB':
                return PerchDB::fetch();
                break;
                
            case 'Lang':
                if ($this->Lang === false) {
                    $this->Lang = new $full_class_name($this->version, $this->app_id);
                }
                return $this->Lang;
                break;

            case 'Settings':
                return PerchSettings::fetch();
                break;
            
            default:
                if ($this->Lang === false) {
                    $this->Lang = new PerchAPI_Lang($this->version, $this->app_id);
                }
                return new $full_class_name($this->version, $this->app_id, $this->Lang);
                break;
        }
        
        return false;
    }

    public function set_app_id_for_nav($app_id)
    {
        $this->nav_app_id = $app_id;
    }
    
    public function app_path($app_id=false)
    {
        if (!$app_id) $app_id = $this->app_id;
        return PERCH_LOGINPATH.'/addons/apps/'.$app_id;
    }

    public function app_nav($app_id=false)
    {
        if (!$app_id) $app_id = $this->nav_app_id;
        return '/addons/apps/'.$app_id;
    }


    public function on($event, $callback)
    {
        $Perch = Perch::fetch();
        $Perch->on($event, $callback);
    }

    public function event()
    {
        $Perch = Perch::fetch();
        
        call_user_func_array(array($Perch, 'event'), func_get_args());
    }
}