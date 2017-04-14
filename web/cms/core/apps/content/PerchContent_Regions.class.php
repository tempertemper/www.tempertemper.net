<?php

class PerchContent_Regions extends PerchFactory
{
    protected $singular_classname  = 'PerchContent_Region';
    protected $table               = 'content_regions';
    protected $pk                  = 'regionID';
    
    protected $default_sort_column = 'regionOrder';  
    
    private $_region_cache         = false;
    private $_regions_preloaded    = false;
    
    

    public function create($data)
    {
        $Region = parent::create($data);
        $Perch = Perch::fetch();
        $Perch->event('region.create', $Region);
        return $Region;
    }

    /**
     * Preload and cache region rows
     *
     * @return void
     * @author Drew McLellan
     */
    public function preload_regions()
    {
        $sql = 'SELECT * FROM '.$this->table.' ORDER BY regionOrder ASC';
        $regions = $this->db->get_rows($sql);
        
        if (PerchUtil::count($regions)) {
            $cache = array();
            foreach($regions as $region) {
                $pageID = $region['pageID'];
                if ($region['regionPage'] == '*') {
                    $pageID = '*';
                }
                
                if (!isset($cache[$pageID])) $cache[$pageID] = array();
                
                $cache[$pageID][] = $region;
            }
            
            $this->_region_cache = $cache;
        }
        $this->_regions_preloaded = true;
        
        return true;
    }
    
    
    /**
     * Get the regions for the page, from cache if possible
     *
     * @param string $pageID
     * @return void
     * @author Drew McLellan
     */
    public function get_for_page($pageID, $include_shared=true, $new_only=false, $template=false, PerchAPI_Paging $Paging = null)
    {
        if ($this->_regions_preloaded) {
            if (isset($this->_region_cache[$pageID])) {
                return $this->return_instances($this->_region_cache[$pageID]);
            }
        }

        $sort_val = null;
        $sort_dir = null;

        if ($Paging && $Paging->enabled()) {
            $sql = $Paging->select_sql();
            list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();
        }else{
            $sql = 'SELECT';
        }
        
        $sql .= ' * FROM '.$this->table.' WHERE pageID='.$this->db->pdb((int)$pageID);
        
        if (!$include_shared) {
            $sql .= ' AND regionPage!='.$this->db->pdb('*');
        }

		if ($new_only) {
			$sql .= ' AND regionNew=1 ';
		}
        
		if ($template) {
			$sql .= ' AND regionTemplate='.$this->db->pdb($template).' ';
		}

        if ($Paging && $Paging->enabled() && $sort_val) {
            $sql .= ' ORDER BY '.$sort_val.' '.$sort_dir;
        } else {
            $sql .= ' ORDER BY regionOrder ASC';    
        }
        
        if ($Paging && $Paging->enabled()) {
            $sql .=  ' '.$Paging->limit_sql();
        }
        
        $rows = $this->db->get_rows($sql);

        if ($Paging && $Paging->enabled()) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }
        
        return $this->return_instances($rows);
    }


    /**
     * Find a region by its pageID and key. Used by the Upgrade app.
     * @param  int $pageID    Page ID
     * @param  string $regionKey Region Key
     * @return object            Instance of Region class
     */
    public function find_for_page_by_key($pageID, $regionKey)
    {
        $sql = 'SELECT * FROM '.$this->table.'
                WHERE pageID='.$this->db->pdb((int)$pageID).'
                        AND regionKey='.$this->db->pdb($regionKey).'
                LIMIT 1';
        $row = $this->db->get_row($sql);

        return $this->return_instance($row);
    }
    
    /**
     * Get regions which are shared across all pages
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_shared(PerchAPI_Paging $Paging = null, $template=false)
    {

        $sort_val = null;
        $sort_dir = null;

        if ($Paging && $Paging->enabled()) {
            $sql = $Paging->select_sql();
            list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();
        }else{
            $sql = 'SELECT';
        }


        $sql .= ' * FROM '.$this->table.' WHERE regionPage='.$this->db->pdb('*');

		if ($template) {
			$sql .= ' AND regionTemplate='.$this->db->pdb($template).' ';
		}

        if (!$sort_val) {
            $sort_val = 'regionKey';
            $sort_dir = 'ASC';
        }

        $sql .= ' ORDER BY '.$sort_val.' '.$sort_dir;

        if ($Paging && $Paging->enabled()) {
            $sql .=  ' '.$Paging->limit_sql();
        }

        $rows = $this->db->get_rows($sql);

        if ($Paging && $Paging->enabled()) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }
        
        return $this->return_instances($rows);
    }
    
    /**
     * Get regions for the given path - used at runtime for rendering draft revisions
     *
     * @param string $regionPage 
     * @return void
     * @author Drew McLellan
     */
    public function get_for_page_path($regionPage)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE regionPage='.$this->db->pdb($regionPage).' OR regionPage=\'*\'';
        $rows = $this->db->get_rows($sql);
        
        return $this->return_instances($rows);
    }
    
    /**
     * Display name for template
     *
     * @param string $file_name 
     * @return void
     * @author Drew McLellan
     */
    public function template_display_name($file_name, $star_hidden=false)
    {
        if ($star_hidden && substr($file_name, 0, 1) == '_') {
            $file_name = '*'.$file_name;
        }

        $file_name = str_replace('.html', '', $file_name);
        $file_name = str_replace('_', ' ', $file_name);
        $file_name = str_replace('-', ' - ', $file_name);
        $file_name = str_replace('/', ' / ', $file_name);
        
        $file_name = ucwords($file_name);
        
        return trim($file_name);
    }
    
    /**
     * Get an array of templates in the content folder.
     *
     * @param string $path 
     * @return void
     * @author Drew McLellan
     */
    public function get_templates($path=false, $include_hidden=false, $initial_path=false)
    {
        $Perch = Perch::fetch();
        
        if ($path===false) $path = PERCH_TEMPLATE_PATH.'/content';
        if ($initial_path===false) $initial_path = $path;
        $a      = array();
        $groups = array();
        $p      = false;
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if(substr($file, 0, 1) != '.' && ($include_hidden || substr($file, 0, 1) != '_') && !preg_match($Perch->ignore_pattern, $file)) {
                        $extension = PerchUtil::file_extension($file);
                        if ($extension == 'html' || $extension == 'htm') {
                            $p = str_replace($initial_path, '', $path);
                            if (!$p) {
                                $a[PerchLang::get('General')][] = array('filename'=>$file, 'value'=>$file, 'path'=>$file, 'label'=>$this->template_display_name($file, true));
                            }else{
                                $a[] = array('filename'=>$file, 'value'=>ltrim($p, '/').'/'.$file, 'path'=>ltrim($p, '/').'/'.$file, 'label'=>$this->template_display_name($file));
                            }
                        }else{
                            // Use this one of infinite recursive nesting. Group stuff below normalised for HTML select optgroups that only do one level
                            //$a[$this->template_display_name($file)] = $this->get_templates($path.'/'.$file, $include_hidden, $initial_path);
                            
                            if ($p) {
                                $group_name = $this->template_display_name(trim($p, '/\\').'/'.$file, true);
                            }else{
                                $group_name = $this->template_display_name($file, true);
                            }
                            
                            $groups[$group_name] = $this->get_templates($path.'/'.$file, $include_hidden, $initial_path);
                        }
                    }
                }
                closedir($dh);
            }
            //PerchUtil::debug($a, 'notice');
            if (PerchUtil::count($a)) {
                if (isset($a[PerchLang::get('General')])) {
                    $a[PerchLang::get('General')] = PerchUtil::array_sort($a[PerchLang::get('General')], 'label'); 
                }else{
                    $a = PerchUtil::array_sort($a, 'label');     
                }
                
            }
        }
        return $a+$groups;
    }
    
    /**
     * Delete any regions with the given key, optionally only new regions. Used when making a region shared.
     *
     * @param string $key 
     * @param string $new_only 
     * @return void
     * @author Drew McLellan
     */
    public function delete_with_key($key, $new_only=false)
    {
        $sql = 'DELETE FROM '.$this->table.'
                WHERE regionKey='.$this->db->pdb($key);
                
        if ($new_only) {
            $sql .= ' AND regionNew=1';
        }
        
        $this->db->execute($sql);
    }

	/**
	 * Get an array of the template file names that are in active use for a region.
	 *
	 * @return void
	 * @author Drew McLellan
	 */
	public function get_templates_in_use()
	{
		$sql = 'SELECT DISTINCT regionTemplate FROM '.$this->table.' ORDER BY regionTemplate ASC';
		$rows = $this->db->get_rows($sql);
		
		return $rows;
	}

    /**
     * Takes the page, region and field name and gets select box options for the dataselect field type
     * @param  string $regionPage Page path
     * @param  string $regionKey  The name of a region, as used in the page
     * @param  string $fieldID    The title of a field
     * @param  string $valueID    If set, the field to use for values
     * @return array             Options array with label and value keys for select field type.
     */
    public function find_data_select_options($regionPage, $regionKey, $fieldID, $valueID=false)
    {
        $sql = 'SELECT * FROM '.$this->table.' 
                WHERE regionPage='.$this->db->pdb($regionPage).'
                    AND regionKey='.$this->db->pdb($regionKey);
        $region = $this->db->get_row($sql);

        if (PerchUtil::count($region)) {

            $Items = new PerchContent_Items;
            $items = $Items->get_for_region($region['regionID'], $region['regionLatestRev']);

            $opts = array();

            if (PerchUtil::count($items)) {
                foreach($items as $Item) {
                    $details = PerchUtil::json_safe_decode($Item->itemJSON());
                    if (is_object($details)) {
                        $tmp = array();

                        $fieldIDs = explode(' ', $fieldID);
                        $label = array();
                        if (PerchUtil::count($fieldIDs)) {
                            foreach($fieldIDs as $field) {
                                if ($details->$field) {
                                    $label[] = $details->$field;
                                }
                            }
                        }

                        if ($label) {
                            $tmp['label'] = implode(' ', $label);

                            if ($valueID && $details->$valueID) {
                                $tmp['value'] = $details->$valueID;
                            }else{
                                $tmp['value'] = $tmp['label'];
                            }
                        }
                        if (count($tmp)) $opts[] = $tmp;

                    }
                }
            }
            return $opts;
        }
    }

    public function republish_all($interactive=false)
    {
        $regions = $this->all();
        if (PerchUtil::count($regions)) {
            
            if ($interactive) flush();

            foreach($regions as $Region) {

                if ($interactive) {
                    echo '<li class="progress-item progress-success">'.PerchUI::icon('core/circle-check').' '.PerchLang::get('Republishing: %s on page %s', $Region->regionKey(), $Region->regionPage()).'</li>';
                }

                $Region->publish();
                $Region->index();

                if ($interactive) flush();
            }

            if ($interactive) {
                echo '<li class="progress-item progress-success">'.PerchUI::icon('core/circle-check').' '.PerchLang::get('Republishing completed successfully').'</li>';
                flush();
            }
        }
    }

    public function modify_permissions($grant_or_revoke='grant', $roleID)
    {
        // get all role IDs
        $sql = 'SELECT roleID FROM '.PERCH_DB_PREFIX.'user_roles WHERE roleID != '.(int)$roleID.' ORDER BY roleID ASC';
        $all_roles_but_this = $this->db->get_rows_flat($sql);

        $Perch = Perch::fetch();

        $regions = $this->all();

        if (PerchUtil::count($regions)) {

            foreach($regions as $Region) {

                if ($grant_or_revoke == 'grant') {

                    if ($Region->regionEditRoles()=='*') {
                        // nothing to change
                        continue;
                    }else{
                        $roles = explode(',', $Region->regionEditRoles());
                        if (!in_array($roleID, $roles)) {
                            $roles[] = $roleID;

                            sort($roles);

                            $Region->update(array(
                                'regionEditRoles' => implode(',', $roles)
                            ));

                            $Perch->event('region.update_permissions', $Region);
                        }
                    }
                }

                if ($grant_or_revoke == 'revoke') {

                    if ($Region->regionEditRoles()=='*') {         
                        $Region->update(array(
                            'regionEditRoles' => implode(',', $all_roles_but_this)
                        ));
                    }else{
                        $roles = explode(',', $Region->regionEditRoles());
                        if (in_array($roleID, $roles)) {
                            $position = array_search($roleID, $roles);
                            unset($roles[$position]);

                            sort($roles);

                            $Region->update(array(
                                'regionEditRoles' => implode(',', $roles)
                            ));

                            $Perch->event('region.update_permissions', $Region);
                        }   
                    }
                }
            }
        }
    }
    
}
