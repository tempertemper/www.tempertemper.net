<?php

class PerchMenuItems extends PerchFactory
{
	protected $singular_classname  = 'PerchMenuItem';
	protected $table               = 'menu_items';
	protected $pk                  = 'itemID';
	protected $default_sort_column = 'itemOrder';  

	public $static_fields = ['parentID', 'itemType', 'itemOrder', 'itemTitle', 'itemValue', 'itemPersists', 'itemActive', 'privID', 'userID'];

	public function get_top_level()
	{
		$sql = 'SELECT * FROM '.$this->table.' WHERE itemType='.$this->db->pdb('menu').' AND parentID=0 ORDER BY itemOrder ASC';
		return $this->return_instances($this->db->get_rows($sql));
		
	}

	public function get_for_parent($parentID)
	{
		$sql = 'SELECT * FROM '.$this->table.' WHERE parentID='.$this->db->pdb($parentID).' AND itemInternal=0 ORDER BY itemOrder ASC';
		return $this->return_instances($this->db->get_rows($sql));
	}

}




