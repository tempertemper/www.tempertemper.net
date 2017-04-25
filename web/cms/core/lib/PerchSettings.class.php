<?php

class PerchSettings extends PerchFactory
{
    protected $singular_classname = 'PerchSetting';
    protected $table    = 'settings';
    protected $pk       = 'settingID';
    protected $default_sort_column = 'settingID';
    
    private $CurrentUser = false;
 
    static protected $instance;
    
    public static function fetch()
	{
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        
        return self::$instance;
	}
 
    
    public function set_user($CurrentUser)
    {
        $this->CurrentUser = $CurrentUser;
    }
 
 
    public function set($settingID, $settingValue, $userID=0)
    {
        $sql = 'DELETE FROM '.$this->table.' WHERE settingID='.$this->db->pdb($settingID).' AND userID='.$this->db->pdb((int)$userID).' LIMIT 1';
        $this->db->execute($sql);
        
        $data   = array();
        $data['settingID']    = $settingID;
        $data['settingValue'] = $settingValue;
        $data['userID']       = $userID;

        $this->db->insert($this->table, $data);
    }
    
    public function get($settingID)
    {
        if ($this->cache === false) {
            
            $CurrentUser = $this->CurrentUser;
            
            if (is_object($CurrentUser) && $CurrentUser->logged_in()) {
                $sql = 'SELECT * FROM (SELECT DISTINCT settingID, settingValue, userID FROM ' . $this->table .' WHERE userID='.$this->db->pdb((int)$CurrentUser->id()).' OR userID=0 ORDER BY userID DESC) AS settings GROUP BY settingID, settingValue, userID';

                $sql = 'SELECT settingID, settingValue, userID FROM ' . $this->table .' WHERE userID='.$this->db->pdb((int)$CurrentUser->id()).' OR userID=0 ORDER BY userID ASC';

            }else{
                $sql = 'SELECT DISTINCT settingID, settingValue FROM ' . $this->table .' WHERE userID=0';
            }
                        
            $rows = $this->db->get_rows($sql);
            $this->cache = array();
            if (PerchUtil::count($rows) > 0) {
                foreach($rows as $row) {
                    $this->cache[$row['settingID']] = $row;
                }
            }
        }
        
        if ($this->cache !== false){
            if (isset($this->cache[$settingID])) {
                return $this->return_instance($this->cache[$settingID]);
            }
        }
        
        // always return something, even if it's just an empty object.
        return $this->return_instance(array('settingID'=>$settingID, 'settingValue'=>''));
    }
    
    public function get_as_array($user_specific=false)
    {
        $CurrentUser = $this->CurrentUser;
        
        if ($user_specific && $CurrentUser->logged_in()) {
            $sql = 'SELECT * FROM (SELECT DISTINCT settingID, settingValue FROM ' . $this->table .' WHERE userID='.$this->db->pdb((int)$CurrentUser->id()).' OR userID=0 ORDER BY userID DESC) AS settings GROUP BY settingID';
        }else{
            $sql = 'SELECT DISTINCT settingID, settingValue FROM ' . $this->table .' WHERE userID=0';
        }
        
        $rows = $this->db->get_rows($sql);
        $out = array();
        if (PerchUtil::count($rows) > 0) {
            foreach($rows as $row) {
                $out[$row['settingID']] = $row['settingValue'];
            }
        }
        
        return $out;
    }
    
    public function reload()
    {
        PerchUtil::debug('Reloading setting data');
        $this->cache = false;
    }
}
