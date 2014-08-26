<?php

class PerchUsers extends PerchFactory
{
    protected $singular_classname  = 'PerchUser';
    protected $table               = 'users';
    protected $pk                  = 'userID';
    protected $default_sort_column = 'userUsername';
    
	static private $current_user;
	

    public function get_current_user()
    {

		if (!isset(self::$current_user)) {
            
			// use a plugin if it's there
	        if (defined('PERCH_AUTH_PLUGIN') && PERCH_AUTH_PLUGIN) {

	            $str = PERCH_AUTH_PLUGIN.'_auth_plugin';

	            if (!class_exists($str)) {
	                require PERCH_PATH.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'auth'.DIRECTORY_SEPARATOR.PERCH_AUTH_PLUGIN.DIRECTORY_SEPARATOR.'auth.php';
	            }

	            $AuthenticatedUser = new $str(array());
	        }else{
	            $AuthenticatedUser   = new PerchAuthenticatedUser(array());
	        }

	        $AuthenticatedUser->recover();

	        self::$current_user = $AuthenticatedUser;

       	}

        return self::$current_user;
        
    }
    
    public function create($data, $send_welcome_email=true)
    {
        
        // check which type of password - default is portable
        if (defined('PERCH_NONPORTABLE_HASHES') && PERCH_NONPORTABLE_HASHES) {
            $portable_hashes = false;
        }else{
            $portable_hashes = true;
        }
        
        $Hasher = new PasswordHash(8, $portable_hashes);
        
        $clear_pwd  = $data['userPassword'];
        $data['userPassword'] = $Hasher->HashPassword($clear_pwd);
        $data['userCreated'] = date('Y-m-d H:i:s');
        $data['userEnabled'] = '1';
        
        $NewUser = parent::create($data);
        
        if (is_object($NewUser) && $send_welcome_email) {
            $NewUser->squirrel('clear_pwd', $clear_pwd);
            $NewUser->send_welcome_email();
        }
        
        return $NewUser;
    }
    
    public function username_available($username, $exclude_userID=false)
    {
        $sql = 'SELECT COUNT(*)
                FROM ' . $this->table . '
                WHERE userUsername='.$this->db->pdb($username);
                
        if ($exclude_userID) {
            $sql .= ' AND userID!='.$this->db->pdb((int) $exclude_userID);
        }
                
        $count  = (int) $this->db->get_value($sql);
        
        if ($count == 0) return true;
        
        return false;
    }

    public function email_available($email, $exclude_userID=false)
    {
        $sql = 'SELECT COUNT(*)
                FROM ' . $this->table . '
                WHERE userEmail='.$this->db->pdb($email);
        
        if ($exclude_userID) {
            $sql .= ' AND userID!='.$this->db->pdb((int) $exclude_userID);
        }
        
        $count  = (int) $this->db->get_value($sql);
        
        if ($count == 0) return true;
        
        return false;
    }
    
    public function find_by_email($email) 
    {
        $sql = 'SELECT *
                FROM '.$this->table.'
                WHERE userEmail='.$this->db->pdb($email).'
                    AND userEnabled=1
                LIMIT 1';
        $row = $this->db->get_row($sql);
        return $this->return_instance($row);
    }
    
    public function get_grouped_listing($Paging=false)
    {
        if ($Paging && $Paging->enabled()) {
            $sql = $Paging->select_sql();
        }else{
            $sql = 'SELECT';
        }
        
        $sql .= ' u.*, ur.* 
                FROM ' . $this->table .' u, '.PERCH_DB_PREFIX.'user_roles ur 
                WHERE u.roleID=ur.roleID
                ORDER BY roleMasterAdmin DESC, userMasterAdmin DESC, roleTitle, userFamilyName ASC, userGivenName ASC';

        
        if ($Paging && $Paging->enabled()) {
            $sql .=  ' '.$Paging->limit_sql();
        }
        
        $results = $this->db->get_rows($sql);
        
        if ($Paging && $Paging->enabled()) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }
        
        return $this->return_instances($results);
    }
    
    
    /**
     * Get all the users for the given role.
     *
     * @param string $roleID 
     * @return void
     * @author Drew McLellan
     */
    public function get_by_role($roleID)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE roleID='.$this->db->pdb($roleID);
        $rows = $this->db->get_rows($sql);
        
        return $this->return_instances($rows);
    }

    public function no_master_admin_exists()
    {
        $sql = 'SELECT COUNT(*)
                FROM ' . $this->table . '
                WHERE userMasterAdmin=1';  
    
        $count  = (int) $this->db->get_value($sql);
        
        if ($count == 0) return true;
        
        return false;
    }
    
    
}

?>