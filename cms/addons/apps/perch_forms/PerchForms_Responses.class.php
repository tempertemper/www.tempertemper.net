<?php

class PerchForms_Responses extends PerchAPI_Factory
{
    protected $singular_classname = 'PerchForms_Response';
    protected $table    = 'forms_responses';
    protected $pk   = 'responseID';
    
    public function get_for_from($formID, $Paging, $spam=false)
    {
        $sql = $Paging->select_sql().' *
                FROM '.$this->table.'
                WHERE formID='.$this->db->pdb((int)$formID).'
                    AND responseSpam='.($spam ? '1' : '0').'
                ORDER BY responseCreated DESC 
                '.$Paging->limit_sql();
                
        $rows    = $this->db->get_rows($sql);
	    
	    if ($Paging->enabled()) {
	        $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
	    }
	        	    
	    return $this->return_instances($rows);
    }

}
