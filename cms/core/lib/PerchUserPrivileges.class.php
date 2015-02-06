<?php

class PerchUserPrivileges extends PerchFactory
{
    protected $singular_classname = 'PerchUserPrivilege';
    protected $table    = 'user_privileges';
    protected $pk   = 'privID';

    protected $default_sort_column  = 'privOrder';
    
    
    /**
     * Get privs grouped and ordered by app
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_for_edit()
    {
        $sql = 'SELECT DISTINCT *, SUBSTRING_INDEX(privKey, \'.\', 1) AS app 
                FROM '.$this->table.'
                ORDER BY app=\'perch\' DESC, app ASC, privOrder ASC';
        return $this->return_instances($this->db->get_rows($sql));
    }  
    
    /**
     * Get a flat array of granted privID for the role, for repopulating checkboxes on edit page
     *
     * @param string $roleID 
     * @return void
     * @author Drew McLellan
     */
    public function get_flat_for_role($Role)
    {
        $roleID = $Role->id();

        if ($Role->roleMasterAdmin()) {
            // Master admin has all privs, so just selected everything unfiltered
            $sql = 'SELECT privID FROM '.$this->table;
        }else{
            $sql = 'SELECT privID FROM '.PERCH_DB_PREFIX.'user_role_privileges
                WHERE roleID='.$this->db->pdb((int)$roleID);
        }

        
        $rows = $this->db->get_rows($sql);
        
        $out = array();
        if (PerchUtil::count($rows)) {
            foreach($rows as $row) {
                $out[] = $row['privID'];
            }
        }
        return $out;
    }

}
