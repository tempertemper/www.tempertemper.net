<?php

class PerchMenu extends PerchFactory
{
	protected $singular_classname  = 'PerchMenuItem';
	protected $table               = 'menu_items';
	protected $pk                  = 'itemID';
	protected $default_sort_column = 'itemOrder';  

	private $details;
	private $items = [];


	public function get_menu($parentID = 0, $index = 0, $count = 1)
	{
		/*
		$sql = 'SELECT * FROM '.$this->table.' WHERE itemActive=1 AND itemType=\'menu\' AND parentID='.$this->db->pdb($parentID)
				.' ORDER BY itemOrder ASC LIMIT '. $index . ', '. $count;
		*/

		$sql = 'SELECT mi.*, p.privKey FROM '.$this->table.' mi LEFT JOIN '.PERCH_DB_PREFIX.'user_privileges p  ON mi.privID=p.privID 
				WHERE mi.itemActive=1 AND mi.parentID='.$this->db->pdb($parentID)
				.' ORDER BY itemOrder ASC LIMIT '. $index . ', '. $count;


		$rows= $this->db->get_rows($sql);

		if (PerchUtil::count($rows)) {
			$out = [];

			foreach($rows as $row) {
				$Menu = new PerchMenu($this->api);
				$Menu->set_details($row);
				$out[] = $Menu;
			}

			return $out;
		}

		return null;
	}

	public function title()
	{
		return $this->details['itemTitle'];
	}

	public function is_permitted($CurrentUser)
	{
		if (is_null($this->details['privKey'])) return true;

		return $CurrentUser->has_priv($this->details['privKey']);
	}

	public function get_items()
	{
		$sql = 'SELECT mi.*, p.privKey FROM '.$this->table.' mi LEFT JOIN '.PERCH_DB_PREFIX.'user_privileges p  ON mi.privID=p.privID 
				WHERE mi.itemActive=1 AND mi.parentID='.$this->db->pdb((int)$this->details['itemID'])
				.' ORDER BY mi.itemOrder ASC';
		$this->items = $this->return_instances($this->db->get_rows($sql));
		return $this->items;
	}

	public function find_app_title($appID)
	{
		$sql = 'SELECT itemTitle FROM '.$this->table.' WHERE itemType='.$this->db->pdb('app').' AND itemValue='.$this->db->pdb($appID).' LIMIT 1';
		return $this->db->get_value($sql);
	}

	public function set_details($row) 
	{
		$this->details = $row;
	}

	public function add_new_apps($apps)
    {   
        if (PerchUtil::count($apps)) {
        	$app_ids = [];
        	foreach($apps as $app) $app_ids[] = $app['id'];
            $sql = 'SELECT itemValue FROM '.$this->table.' WHERE itemType=\'app\'';
            $menu_app_ids = $this->db->get_rows_flat($sql);

            $top_menu = null;

            if ($menu_app_ids) {
            
	            foreach($app_ids as $app) {
	            	if (!in_array($app, $menu_app_ids)) {
	            		// Unknown app, create it
	            		foreach($apps as $app_profile) {
	            			if ($app_profile['id'] == $app) {
	            				if (is_null($top_menu)) {
	            					$top_menu = $this->db->get_value('SELECT itemID FROM '.$this->table.' WHERE parentID=0 AND itemActive=1 AND itemType="menu" ORDER BY itemOrder ASC LIMIT 1');
	            				}


	            				$this->create([
										'parentID'     => $top_menu,
										'itemType'     => 'app',
										'itemOrder'    => 99,
										'itemTitle'    => $app_profile['label'],
										'itemValue'    => $app,
										'itemPersists' => 0,
										'itemActive'   => ($app_profile['hidden'] ? 0 : 1),
										'privID'       => null,
										'userID'       => 0,
										'itemInternal' => 0,
	            					]);
	            			}
	            		}
	            	}
	            } 

	        }

        }

        return;
    }

    public function rebuild($CurrentUser)
    {
    	$Perch = Perch::fetch();
    	$apps = $Perch->find_installed_apps($CurrentUser);
    	$apps_ids = [];
    	if (PerchUtil::count($apps)) {
    		foreach($apps as $app) {
    			$app_ids[] = $app['id'];
    		}
    	}
    	
    	$sql = 'DELETE FROM '.$this->table.' WHERE itemType='.$this->db->pdb('app').' AND itemValue NOT IN ('.$this->db->implode_for_sql_in($app_ids).') AND itemInternal=0 AND itemPersists=0';

    	$this->db->execute($sql);
    }

}
