<?php

class PerchAPI_AuthPlugin extends PerchBase
{
	protected $table = 'YOUR_USER_TABLE';
    protected $pk = 'userID';
    
    private $logged_in = false;

	public $activation_failed = false;

	private $privileges = [];
	private $buckets    = null;

	private $Role;
	
    public function log_user_in($username, $password)
	{
		die('Auth plugin '. PERCH_AUTH_PLUGIN .' needs to implement method log_user_in($username, $password)');
		return false;
	}
	
	
    public function resume_session()
	{
		die('Auth plugin '. PERCH_AUTH_PLUGIN .' needs to implement method resume_session()');
		return false;
	}
	
	public function authenticate($username, $password)
	{
		$user_details = $this->log_user_in($username, $password);
		
		if (is_array($user_details)) {
			$this->logged_in = true;

			$Roles = new PerchUserRoles;
			$Role = $Roles->get_one_by('roleSlug', $user_details['role']);
			$this->Role = $Role;

			$details = array();
			$details['userID']    = $user_details['email'];
			$details['userEmail'] = $user_details['email'];
			$details['roleID']    = $Role->id();
			$details['roleSlug']  = $Role->roleSlug();
			$details['userMasterAdmin'] = $Role->roleMasterAdmin();
			$details['roleMasterAdmin'] = $Role->roleMasterAdmin();
			$this->set_details($details);		

			$this->_load_privileges($Role);

			// activate
			$AuthenticatedUser = new PerchAuthenticatedUser(array());
			if ($AuthenticatedUser->activate_from_plugin()) {
				return true;
			}else{
				$this->activation_failed = true;
			}
		}
		
		$this->logged_in = false;
		return false;
	}
	
	public function recover()
	{
		$start_session_for_internal  = PerchSession::is_set('userID');

		$user_details = $this->resume_session();
		
		if (is_array($user_details)) {
			$this->logged_in = true;

			$Roles = new PerchUserRoles;
			$Role = $Roles->get_one_by('roleSlug', $user_details['role']);
			$this->Role = $Role;

			$details = array();
			$details['userID']    = $user_details['email'];
			$details['userEmail'] = $user_details['email'];
			$details['roleID']    = $Role->id();
			$details['roleSlug']  = $Role->roleSlug();
			$details['userMasterAdmin'] = $Role->roleMasterAdmin();
			$details['roleMasterAdmin'] = $Role->roleMasterAdmin();
			$this->set_details($details);

			$AuthenticatedUser = new PerchAuthenticatedUser(array());

			$this->_load_privileges($Role);

			return true;
		}
		
		$this->logged_in = false;
		return false;
	}
	
    public function logged_in()
	{
		return $this->logged_in;
	}
	
	public function logout()
	{
		$this->logged_in = false;
        PerchSession::delete('userID');
        PerchSession::delete('userHash');
		$this->log_user_out();
		
		return true;
	}
	
	public function id()
    {
        return $this->details['userEmail'];
    }

    public function has_priv($priv)
	{
		if ($this->Role && $this->Role->roleMasterAdmin()) return true;
	    return in_array($priv, $this->privileges);
	}

	public function get_privs()
    {
        return $this->privileges;
    }

    public function can_use_bucket($bucket, $privs=array(), $check='any')
    {
        if ($this->roleMasterAdmin()) {
            // master admin can use everything, no need to check
            return true;
        }

        if ($this->buckets === null) {
            // lazy load the bucket permissions
            $this->_load_bucket_privileges();
        }

        if (count($privs) === 0) {
            $privs = ['select', 'insert', 'update', 'delete'];
        }

        if (count($this->buckets) === 0) {
            // privs aren't explicitly set for this role, so allow it. Permissive by default.
            return true;
        }

        // if matching all privs
        if ($check == 'all') {
            $common = array_intersect($privs, (isset($this->buckets[$bucket]) ? $this->buckets[$bucket] : []));
            if (count($common) == count($privs)) {
                return true;
            }
            return false;    
        }

        // match any privs
        foreach($privs as $priv) {
            if (!isset($this->buckets[$bucket])) {
                return false;
            }

            if (in_array($priv, $this->buckets[$bucket])) {
                return true;
            }
        }

        return false;
    }

    public function get_privs_for_bucket($bucket)
    {
        $privs = ['select', 'insert', 'update', 'delete'];

        if ($this->buckets === null) {
            // lazy load the bucket permissions
            $this->_load_bucket_privileges();
        }

        if (!isset($this->buckets[$bucket])) {
            if (count($this->buckets)) {
                return [];
            }
        }

        if (isset($this->buckets[$bucket])) {
            return $this->buckets[$bucket];
        }

        return $privs;
    }

	private function _load_privileges($Role)
	{
        if ($Role->roleMasterAdmin()) {
            $sql = 'SELECT p.privKey FROM '.PERCH_DB_PREFIX.'user_privileges p';
        }else{
            $sql = 'SELECT p.privKey FROM '.PERCH_DB_PREFIX.'user_role_privileges rp, '.PERCH_DB_PREFIX.'user_privileges p
                WHERE rp.privID=p.privID AND rp.roleID='.$this->db->pdb((int)$Role->id());
        }

	    $rows = $this->db->get_rows($sql);
	    if (PerchUtil::count($rows)) {
	        $privs = array();
	        foreach($rows as $row) {
	            $privs[] = $row['privKey'];
	        }
	        $this->privileges = $privs;
	    }
	}

	private function _load_bucket_privileges()
    {
        if (!PERCH_RUNWAY) {
            $this->buckets = [];
            return;
        }

        // bucket privs
        if (!$this->roleMasterAdmin()) {
            $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'user_role_buckets WHERE roleID='.$this->db->pdb((int)$this->roleID());
            $rows = $this->db->get_rows($sql);
            if (PerchUtil::count($rows)) {
                $privs = ['roleSelect', 'roleInsert', 'roleUpdate', 'roleDelete', 'roleDefault'];
                $this->buckets = [];
                foreach($rows as $row) {
                    $tmp = [];
                    foreach($privs as $priv) {
                        if ($row[$priv]) {
                            $tmp[] = str_replace('role', '', strtolower($priv));
                        }    
                    }       
                    $this->buckets[$row['bucket']] = $tmp;
                }            
            }
        }

        if ($this->buckets === null) {
            $this->buckets = [];
        }
    }

}
