<?php

class PerchAPI_Factory extends PerchFactory
{
    
    protected $api = false;
    public $static_fields = array();
    
    function __construct($api=false)
    {
        if ($api) $this->api = $api;
        
        parent::__construct($api);
    }
    
    public function create($data)
    {
        $r = parent::create($data);

        if (is_object($r)) {
            $r->log_resources();
        }

        return $r;
    }
    
    public function attempt_install()
    {
        PerchUtil::debug('Attempting app installation: '.$this->api->app_id);

        $sql = 'SHOW TABLES LIKE "'.$this->table.'"';
        $result = $this->db->get_value($sql);
        
        if ($result==false) {
            $activation_file = PerchUtil::file_path(PERCH_PATH.'/addons/apps/'.$this->api->app_id.'/activate.php');
            if (file_exists($activation_file)) {
                $API = $this->api;
                return (include ($activation_file));
            }
        }
        
        return false; 
    }
    
}
