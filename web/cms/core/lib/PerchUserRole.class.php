<?php

class PerchUserRole extends PerchBase
{
    protected $table  = 'user_roles';
    protected $pk     = 'roleID';

    /**
     * Replace existing privs with those IDs in privIDs
     *
     * @param string $privIDs 
     * @return void
     * @author Drew McLellan
     */
    public function set_privileges($privIDs)
    {
        $this->db->delete(PERCH_DB_PREFIX.'user_role_privileges', 'roleID', $this->id());
        
        if (PerchUtil::count($privIDs)) {
            foreach($privIDs as $privID) {
                $data = array();
                $data['privID'] = $privID;
                $data['roleID'] = $this->id();
                $this->db->insert(PERCH_DB_PREFIX.'user_role_privileges', $data);
            }
        }
        
    }

    public function clear_bucket_privs()
    {
        if (PERCH_RUNWAY) {
            $this->db->delete(PERCH_DB_PREFIX.'user_role_buckets', 'roleID', $this->id());
        }
    }

    public function set_bucket_privs($bucket, $data) 
    {
        if (PERCH_RUNWAY) {
            $data['bucket'] = $bucket;
            $data['roleID'] = $this->id();
            $this->db->insert(PERCH_DB_PREFIX.'user_role_buckets', $data);
        }
    }

    public function get_bucket_privs_for_edit()
    {
        $sql = 'SELECT bucket, roleSelect, roleInsert, roleUpdate, roleDelete, roleDefault
                FROM '.PERCH_DB_PREFIX.'user_role_buckets WHERE roleID='.$this->db->pdb((int)$this->id());
        
        $rows = $this->db->get_rows($sql);

        $privs = ['roleSelect', 'roleInsert', 'roleUpdate', 'roleDelete', 'roleDefault'];

        if (PerchUtil::count($rows)) {
            $out = [];

            foreach($rows as $row) {
                $tmp = [];
                foreach($privs as $priv) {
                    if ($row[$priv]) {
                        $tmp[] = str_replace('role', '', strtolower($priv));
                    }    
                }
                
                $out[$row['bucket']] = $tmp;
            }

            return $out;
        }

        return false;
    }
    
    /**
     * Delete role and cascade 
     *
     * @return void
     * @author Drew McLellan
     */
    public function delete()
    {
        // delete privs
        $this->db->delete(PERCH_DB_PREFIX.'user_role_privileges', 'roleID', $this->id());
        if (PERCH_RUNWAY) $this->db->delete(PERCH_DB_PREFIX.'user_role_buckets', 'roleID', $this->id());
        
        // delete self
        $this->db->delete($this->table, $this->pk, $this->details[$this->pk]);
    }
    
    
    /**
     * Shift all users of this role over to the new role given.
     *
     * @param string $new_roleID 
     * @return void
     * @author Drew McLellan
     */
    public function migrate_users($new_roleID)
    {
        $Users = new PerchUsers;
        $users = $Users->get_by_role($this->id());
        
        $data = array();
        $data['roleID'] = $new_roleID;
        
        if (PerchUtil::count($users)) {
            foreach($users as $User) {
                $User->update($data);
            }
        }
        
        return true;
    }

}
