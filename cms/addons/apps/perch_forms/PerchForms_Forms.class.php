<?php

class PerchForms_Forms extends PerchAPI_Factory
{
    protected $singular_classname = 'PerchForms_Form';
    protected $table    = 'forms';
    protected $pk   = 'formID';

    public function find_by_key($key)
    {
        $sql = 'SELECT * 
                FROM '.$this->table.'
                WHERE formKey='.$this->db->pdb($key).'
                LIMIT 1';
        $row = $this->db->get_row($sql);
        
        return $this->return_instance($row);
    }

}

?>