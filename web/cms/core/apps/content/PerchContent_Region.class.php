<?php

class PerchContent_Region extends PerchBase
{
    protected $table  = 'content_regions';
    protected $pk     = 'regionID';

    public $tmp_url_vars = '';

    private $options  = false;
    private $current_userID = false;

    private $history_items = 8; // Number of undos. Overridden by PERCH_UNDO_BUFFER


    function __construct($details) 
    {        
        if (defined('PERCH_UNDO_BUFFER')) $this->history_items = (int)PERCH_UNDO_BUFFER;
        return parent::__construct($details);
    }

    public function delete()
    {
        $Items = new PerchContent_Items;
        $Items->delete_for_region($this->id());

        $r = parent::delete();

        $Perch = Perch::fetch();
        $Perch->event('region.delete', $this);

        return $r;
    }


    /**
     * Get a flat array of items
     *
     * @param string $item_id 
     * @return void
     * @author Drew McLellan
     */
    public function get_items_for_editing($item_id=false, $Paging=false)
    {
        $Items = new PerchContent_Items;
        $Template = new PerchTemplate('content/'.$this->regionTemplate(), 'content');
        return $Items->get_flat_for_region($this->id(), $this->regionLatestRev(), $item_id, $Paging, $Template);
    }

    /**
     * Get item object instances for doing an update
     *
     * @param string $item_id 
     * @return void
     * @author Drew McLellan
     */
    public function get_items_for_updating($item_id=false)
    {
        return $this->get_items($item_id);
    }
    
    /**
     * Get items in region
     *
     * @param string $item_id 
     * @return void
     * @author Drew McLellan
     */
    public function get_items($item_id=false)
    {
        $Items = new PerchContent_Items;
        return $Items->get_for_region($this->id(), $this->regionLatestRev(), $item_id);
    }

    /**
     * Get a list of revisions for the item, for showing the Revision History
     * @return [type]           [description]
     */
    public function get_revisions()
    {
        $Items = new PerchContent_Items;
        return $Items->get_revisions_for_region($this->id());
    }


	/**
	 * Get a count of the number of items for this rev of the region
	 *
	 * @return void
	 * @author Drew McLellan
	 */
	public function get_item_count()
	{
		$Items = new PerchContent_Items;
		return $Items->get_count_for_region($this->id(), $this->regionLatestRev());
	}

    
    /**
     * Set the current userID. Stored against edits.
     *
     * @param string $userID 
     * @return void
     * @author Drew McLellan
     */
    public function set_current_user($userID)
    {
        $this->current_userID = $userID;
    }
    
    /**
     * Are there items in the history stack to undo?
     *
     * @return void
     * @author Drew McLellan
     */
    public function is_undoable()
    {
        // A region is always undoable. Fancy that.
        return true;
    }

    
    /**
     * Does the region have a newer draft than the published version?
     *
     * @return void
     * @author Drew McLellan
     */
    public function has_draft()
    {
        return ((int)$this->regionLatestRev() > (int)$this->regionRev());
    }
    
    /**
     * Does the given roleID have permission to edit this region?
     *
     * @param string $roleID 
     * @return void
     * @author Drew McLellan
     */
    public function role_may_edit($User)
    {

        if ($User->roleMasterAdmin()) return true;

        $roleID = $User->roleID();

        $str_roles = $this->regionEditRoles();
    
        if ($str_roles=='*') return true;
        
        $roles = explode(',', $str_roles);

        return in_array($roleID, $roles);
    }

    /**
     * Does the current role have permission to even see this region?
     * @param  obj $User     User object
     * @param  obj $Settings Settings object
     * @return bool           View or not
     */
    public function role_may_view($User, $Settings)
    {
        if ($this->role_may_edit($User)) return true;

        if ($Settings->get('content_hideNonEditableRegions')->val()) return false;

        return true;
    }
    
    /**
     * Get region options
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_options()
    {
        if (is_array($this->options)) return $this->options;
        $arr = PerchUtil::json_safe_decode($this->regionOptions(), true);
        if (!is_array($arr)) $arr = array();
        $this->options = $arr;
        return $arr;
    }
    
    /**
     * Get an option by key
     *
     * @param string $optKey 
     * @return void
     * @author Drew McLellan
     */
    public function get_option($optKey)
    {
        $options = $this->get_options();
        if (array_key_exists($optKey, $options)) {
            $opt = $options[$optKey];
            if ($opt === 'false') return false;
            return $opt;
        }
        return false;
    }
    
    /**
     * Set region options
     *
     * @param string $options 
     * @return void
     * @author Drew McLellan
     */
    public function set_options($options)
    {
        $existing = $this->get_options();
        if (!is_array($existing)) $existing = array();
        
        $opts = array_merge($existing, $options);
        
        $data = array();
        $data['regionOptions'] = PerchUtil::json_safe_encode($opts);
        $this->update($data);
        
        // clear cache
        $this->options = false;
    }
    
    /**
     * Set a single option
     *
     * @param string $optKey 
     * @param string $val 
     * @return void
     * @author Drew McLellan
     */
    public function set_option($optKey, $val)
    {
        return $this->set_options(array($optKey=>$val));
    }
    
    
    /**
     * Add a new, empty item to the region
     *
     * @return void
     * @author Drew McLellan
     */
    public function add_new_item()
    {
        //$this->create_new_revision();
        
        $new_item   = array(
            'itemID'=>$this->_get_next_item_id(),
            'regionID'=>$this->id(),
            'pageID'=>$this->pageID(),
            'itemRev'=>$this->regionLatestRev(),
            'itemJSON'=>'',
            'itemSearch'=>''
        );
        
        if ($this->get_option('addToTop')==true) {
            $new_item['itemOrder'] = $this->get_lowest_item_order()-1;
        }else{
            $new_item['itemOrder'] = $this->get_highest_item_order()+1;
        }

        $Items = new PerchContent_Items();
        $Item = $Items->create($new_item);
        
        $Perch = Perch::fetch();
        $Perch->event('region.add_item', $this);

        return $Item;
    }
    
    
    /**
     * Delete an item. If the current revision is not a draft, publish the region too.
     *
     * @param string $itemID 
     * @return void
     * @author Drew McLellan
     */
    public function delete_item($itemID)
    {
        $is_draft   = $this->has_draft();
        
        $new_rev    = $this->create_new_revision();
        
        $Items = new PerchContent_Items();
        
        $Item = $Items->find_item($this->id(), $itemID, $new_rev);
        
        $Item->delete();
        
        if (!$is_draft) {
            $this->publish($new_rev);
        }

        return true;
        
    }
    
    
    /**
     * Delete items, leaving only x items in the region. Used for converting multi-item to single item. Undoable.
     *
     * @param string $resulting_item_count 
     * @return void
     * @author Drew McLellan
     */
    public function truncate($resulting_item_count=1)
    {
        $new_rev = $this->create_new_revision();
        $Items = new PerchContent_Items();
        $Items->truncate_for_region($this->id(), $new_rev, $resulting_item_count);

        $Perch = Perch::fetch();
        $Perch->event('region.truncate', $this);
    }
    
    /**
     * Make a region into a shared region
     *
     * @return void
     * @author Drew McLellan
     */
    public function make_shared()
    {    
        $data = array();
    	$data['regionPage'] = '*';
    	$this->update($data);
    	
    	$Regions = new PerchContent_Regions;
    	$Regions->delete_with_key($this->regionKey(), true);

        $Perch = Perch::fetch();
        $Perch->event('region.share', $this);
    }
    
    /**
     * Unshare the region, reverting to original page where possible
     *
     * @return void
     * @author Drew McLellan
     */
    public function make_not_shared()
    {        
        $Pages = new PerchContent_Pages;
        $Page = $Pages->find($this->pageID());
        
        if (is_object($Page)) {
            $data = array();
            $data['regionPage'] = $Page->pagePath();
            $this->update($data);
            
            $Perch = Perch::fetch();
            $Perch->event('region.unshare', $this);

            return true;
        }
        
        return false;
    }

    
    /**
     * Duplicate all content items to create a new revision
     *
     * @return void
     * @author Drew McLellan
     */
    public function create_new_revision($copy_resources=true)
    {
        $old_rev = (int) $this->regionLatestRev();
        $new_rev = $old_rev+1;
        
        $Items = new PerchContent_Items();
        $Items->create_new_revision($this->id(), $old_rev, $new_rev, $copy_resources);
        
        
        $data = array();
        $data['regionLatestRev'] = $new_rev;
        
        // if this is a new region
        if ($new_rev==1) {
            $data['regionRev'] = $new_rev;
        }
        
        $this->update($data);

        $Items->delete_old_revisions($this->id(), $this->history_items);

        $Perch = Perch::fetch();
        $Perch->event('region.create_revision', $this);
        
        return $new_rev;
    }
    
    /**
     * Reorder the items in the region based on the sortField option.
     *
     * @return void
     * @author Drew McLellan
     */
    public function sort_items()
    {
        $sortField = $this->get_option('sortField');
        
        // Sort order
        if ($sortField && $sortField!='') {
            
            $sortOrder = $this->get_option('sortOrder');
            
            $desc = false;
            if ($sortOrder && strtoupper($sortOrder)=='DESC') {
                $desc = true;
            }
            
            $Items = new PerchContent_Items();
            
            $Items->sort_for_region($this->id(), $this->regionLatestRev(), $sortField, $desc);        
        }
    }
    
    /**
     * Generate HTML for region, make current revision non-draft.
     *
     * @return void
     * @author Drew McLellan
     */
    public function publish($rev=false, $change_latest=true)
    {
        if ($rev===false) $rev = $this->regionLatestRev();
        
        $html = $this->render($rev);
        
        $data = array();
        $data['regionHTML']      = $html;
        $data['regionRev']       = $rev;
        
        if ($change_latest) {
            $data['regionLatestRev'] = $rev;
        }
        
        $this->update($data);

        $Perch = Perch::fetch();
        $Perch->event('region.publish', $this);
    }
    
    /**
     * Render the output HTML for the given revision (or latest if not specified)
     *
     * @param string $rev 
     * @return void
     * @author Drew McLellan
     */
    public function render($rev=false)
    {
        if ($rev===false) $rev = $this->regionLatestRev();
        
        // get limit
        $limit = false;

        $set_limit = (int)$this->get_option('limit');
        if ($set_limit>0) {
            $limit = $set_limit;
        }


        $Items = new PerchContent_Items();
        $vars  = $Items->get_flat_for_region($this->id(), $rev, false, $limit);
        
        $Template = new PerchTemplate('content/'.$this->regionTemplate(), 'content');
        
        return $Template->render_group($vars, true);
    }

    /**
     * Add the content of this region into the content index
     * @param  boolean $item_id [description]
     * @param  boolean $rev [description]
     * @return [type]       [description]
     */
    public function index($rev=false)
    {
        if ($rev===false) $rev = $this->regionLatestRev();

        PerchUtil::mb_fallback();

        $Items = new PerchContent_Items();

        // clear out old items
        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'content_index 
                WHERE regionID='.$this->db->pdb((int)$this->id()).' AND itemRev<'.$this->db->pdb((int)$Items->get_oldest_rev($this->id()));
        $this->db->execute($sql);

        $items  = $Items->get_for_region($this->id(), $rev);

        if (PerchUtil::count($items)) {

            $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'content_index 
                    WHERE regionID='.$this->db->pdb((int)$this->id()).' AND itemRev='.$this->db->pdb((int)$rev);
            $this->db->execute($sql);
        

            $Template = new PerchTemplate('content/'.$this->regionTemplate(), 'content');
            $tags = $Template->find_all_tags_and_repeaters('content');

            $tag_index = array();
            if (PerchUtil::count($tags)) {
                foreach($tags as $Tag) {
                    if (!isset($tag_index[$Tag->id()])) {
                        $tag_index[$Tag->id()] = $Tag;
                    }
                }
            }


            foreach($items as $Item) {

                $fields = PerchUtil::json_safe_decode($Item->itemJSON(), true);
                
                $sql = 'INSERT INTO '.PERCH_DB_PREFIX.'content_index (itemID, regionID, pageID, itemRev, indexKey, indexValue) VALUES ';
                $values = array();

                $id_set = false;

                if (PerchUtil::count($fields)) {
                    foreach($fields as $key=>$value) { 
                        if (isset($tag_index[$key])) {
                            $tag = $tag_index[$key];

                            if ($tag->no_index()) {
                                continue;
                            }

                            if ($tag->type()=='PerchRepeater') {
                                $index_value = $tag->get_index($value);
                            }else{
                                $FieldType = PerchFieldTypes::get($tag->type(), false, $tag);
                                $index_value = $FieldType->get_index($value);
                            }

                            
                            if (is_array($index_value)) {
                                foreach($index_value as $index_item) {
                                    $data = array();
                                    $data['itemID']     = (int) $Item->itemID();
                                    $data['regionID']   = (int) $this->id();
                                    $data['pageID']     = (int) $Item->pageID();
                                    $data['itemRev']    = (int) $Item->itemRev();
                                    $data['indexKey']   = $this->db->pdb(mb_substr($index_item['key'], 0, 64));
                                    $data['indexValue'] = $this->db->pdb(mb_substr($index_item['value'], 0, 255));

                                    $values[] = '('.implode(',', $data).')';

                                    if ($index_item['key']=='_id') $id_set = true;

                                }
                            }
                        }
                    }
                }

                // _id
                if (!$id_set) {
                    $data = array();
                    $data['itemID']     = (int) $Item->itemID();
                    $data['regionID']   = (int) $this->id();
                    $data['pageID']     = (int) $Item->pageID();
                    $data['itemRev']    = (int) $Item->itemRev();
                    $data['indexKey']   = $this->db->pdb('_id');
                    $data['indexValue'] = (int) $Item->itemID();

                    $values[] = '('.implode(',', $data).')';
                } 
                
                
                // natural order
                $data = array();
                $data['itemID']     = (int) $Item->itemID();
                $data['regionID']   = (int) $this->id();
                $data['pageID']     = (int) $Item->pageID();
                $data['itemRev']    = (int) $Item->itemRev();
                $data['indexKey']   = $this->db->pdb('_order');
                $data['indexValue'] = $this->db->pdb($Item->itemOrder());

                $values[] = '('.implode(',', $data).')';

                $sql .= implode(',', $values);
                $this->db->execute($sql);

            }    
        

        }

        // optimize index 
        $sql = 'OPTIMIZE TABLE '.PERCH_DB_PREFIX.'content_index';
        $this->db->get_row($sql);
        

        $Perch = Perch::fetch();
        $Perch->event('region.index', $this);
    }

    /**
     * An undo
     *
     * @return void
     * @author Drew McLellan
     */
    public function revert_most_recent()
    {
        $undo_rev = $this->regionLatestRev();
        
        $Items = new PerchContent_Items();
        $prev_rev = $Items->get_previous_revision_number($this->id(), $undo_rev);
        
        if ($prev_rev) {
            $this->publish($prev_rev);
        
            $Items->delete_revision($this->id(), $undo_rev);

            $Perch = Perch::fetch();
            $Perch->event('region.undo', $this);
            
            return true;
        }
        
        return false;
    }

    /**
     * Roll back to a specific revision (Runway)
     * @param  [type] $rev [description]
     * @return [type]      [description]
     */
    public function roll_back($rev)
    {
        if (!PERCH_RUNWAY) return false;

        if ($this->regionRev()<$this->regionLatestRev()) {
            $this->publish($rev, false);    
        }else{
            
            $this->publish($rev, true);

            $Items = new PerchContent_Items();
            $Items->delete_revisions_newer_than($this->id(), $rev);
        }

        
        $Perch = Perch::fetch();
        $Perch->event('region.rollback', $this);

        return true;
    }
    
    public function get_lowest_item_order()
    {
        $Items = new PerchContent_Items();
        return $Items->get_order_bound($this->id(), $this->regionLatestRev(), true);
    }
    
    public function get_highest_item_order()
    {
        $Items = new PerchContent_Items();
        return $Items->get_order_bound($this->id(), $this->regionLatestRev(), false);
    }


    public function clean_up_resources()
    {
        $subquery = 'SELECT resourceID FROM '.PERCH_DB_PREFIX.'resource_log WHERE appID='.$this->db->pdb('content');

        $Resources = new PerchResources;
        $resources = $Resources->get_not_in_subquery('content', $subquery);

        if (PerchUtil::count($resources)) {
            foreach($resources as $Resource) {
                if ($Resource->is_not_in_use()) {
                    $Resource->delete();    
                }
            }
        }

        $Perch = Perch::fetch();
        $Perch->event('region.cleanup', $this);
    }

    public function get_edit_columns()
    {
        $column_ids = $this->get_option('column_ids');

        if ($column_ids) {
            if (is_array($column_ids)) {
                $cols = $column_ids;
            } else {
                $cols = explode(',', $column_ids);    
            }
            

            $Template = new PerchTemplate('content/'.$this->regionTemplate(), 'content');

            $out = array();

            foreach($cols as $col) {
                $col = trim($col);
                $output = false;

                if (strpos($col, '[')) {
                    $parts = explode('[', $col);
                    $col = $parts[0];
                    $output = trim($parts[1], ']');
                }

                $Tag = $Template->find_tag($col, $output);

                if (is_object($Tag)) {
                    $label = $col;
                    if ($Tag->label()) {
                        $label = $Tag->label();
                    }

                    $out[] = array(
                                'id'=>$col,
                                'title'=>$label,
                                'Tag'=>$Tag,
                            );
                }else{
                    $label = $col;
                    if ($label=='_title') {
                        $label = PerchLang::get('Title');
                    }

                    $out[] = array(
                                'id'=>$col,
                                'title'=>$label,
                                'Tag'=>false,
                            );
                }
            }
            return $out;
        }

        return array(array(
                'id' => '_title', 
                'title' => PerchLang::get('Title'),
                'Tag' => false,
                ));
    }

    public function get_template_tag_ids()
    {
        $Template = new PerchTemplate('content/'.$this->regionTemplate(), 'content');
        return $Template->find_all_tag_ids();
    }

    // Used for custom searchURLs e.g. /example.php?id={_id}
    public function substitute_url_vars($matches)
    {
        $url_vars = $this->tmp_url_vars;
        if (isset($url_vars[$matches[1]])){
            return $url_vars[$matches[1]];
        }
    }

    public function to_api_array()
    {
        $out = [
            'id'           => (int)$this->id(),
            'key'          => $this->regionKey(),
            'page'         => $this->regionPage(),
            'page_id'      => (int)$this->pageID(),
            'order'        => (int)$this->regionOrder(),
            'template'     => $this->regionTemplate(),
            'rev'          => (int)$this->regionLatestRev(),
            'last_updated' => $this->regionUpdated(),
        ];

        return $out;
    }
    
    private function _get_next_item_id()
    {
        $Items = new PerchContent_Items();
        return $Items->get_next_id();
    }

}