<?php

class PerchContent_Collection extends PerchBase
{
	    protected $table  = 'collections';
	    protected $pk     = 'collectionID';

	    public $tmp_url_vars = '';

	    private $options  = false;
	    private $current_userID = false;

	    private $history_items = 8; // Number of undos. Overridden by PERCH_UNDO_BUFFER


	    function __construct($details) 
	    {        
	        if (defined('PERCH_UNDO_BUFFER')) $this->history_items = (int)PERCH_UNDO_BUFFER;
	        return parent::__construct($details);
	    }

	    public function to_api_array()
	    {
	    	$out = [
				'id'           => $this->collectionID(),
				'key'          => $this->collectionKey(),
				'order'        => $this->collectionOrder(),
				'template'     => $this->collectionTemplate(),
				'searchable'   => $this->collectionSearchable(),
				'last_updated' => $this->collectionUpdated(),
	    	];	

	    	return $out;
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
	        $Items = new PerchContent_CollectionItems;
	        $Template = new PerchTemplate('content/'.$this->collectionTemplate(), 'content');
	        return $Items->get_flat_for_collection($this->id(), 'latest', $item_id, $Paging, false, $Template);
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
	        return $this->get_items($item_id, 'latest');
	    }
	    
	    /**
	     * Get items in region
	     *
	     * @param string $item_id 
	     * @return void
	     * @author Drew McLellan
	     */
	    public function get_items($item_id=false, $rev=false)
	    {
	        $Items = new PerchContent_CollectionItems;
	        return $Items->get_for_collection($this->id(), $rev, $item_id);
	    }

	    public function get_items_sorted($order, $item_id=false, $rev=false)
	    {
	    	//PerchUtil::debug($order);

	    	if (PerchUtil::count($order)) {
	    		$sql = 'CASE r.itemID ';

	    		for ($i=0; $i<count($order); $i++) {
	    			$sql .= ' WHEN '. $order[$i] .' THEN '.($i+1);
	    		}

	    		$sql .= ' ELSE ' .($i+1). ' END, ';
	    	}


	        $Items = new PerchContent_CollectionItems;	        
	        return $Items->get_for_collection($this->id(), $rev, $item_id, $sql);
	    }

	    /**
	     * Get a list of revisions for the item, for showing the Revision History
	     * @return [type]           [description]
	     */
	    public function get_revisions()
	    {
	        $Items = new PerchContent_CollectionItems;
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
			$Items = new PerchContent_CollectionItems;
			return $Items->get_count_for_collection($this->id());
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
	     * Does the given roleID have permission to edit this collection?
	     *
	     * @param string $roleID 
	     * @return void
	     * @author Drew McLellan
	     */
	    public function role_may_edit($User)
	    {
	    	/*
				Changes here should also be reflected in 
				PerchRunway::find_collections_for_app_menu()
	    	*/
				
	        if ($User->roleMasterAdmin()) return true;

	        $roleID = $User->roleID();

	        $str_roles = $this->collectionEditRoles();
	    
	        if ($str_roles=='*') return true;
	        
	        $roles = explode(',', $str_roles);

	        return in_array($roleID, $roles);
	    }

	    /**
	     * Does the given roleID have permission to publish this collection?
	     *
	     * @param string $roleID 
	     * @return void
	     * @author Drew McLellan
	     */
	    public function role_may_publish($User)
	    {				
	        if ($User->roleMasterAdmin()) return true;

	        $roleID = $User->roleID();

	        $str_roles = $this->collectionPublishRoles();
	    
	        if ($str_roles=='*') return true;
	        
	        $roles = explode(',', $str_roles);

	        return in_array($roleID, $roles);
	    }

	    /**
	     * Does the current role have permission to even see this collection?
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
	        $arr = PerchUtil::json_safe_decode($this->collectionOptions(), true);
	        if (!is_array($arr)) $arr = array();
	        $this->options = $arr;
	        return $arr;
	    }
	    
	    /**
	     * Get an option by key
	     *
	     * @param string $optKey 
	     * @return string|bool
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
	        $data['collectionOptions'] = PerchUtil::json_safe_encode($opts);
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
	     * Add a new, empty item to the collection
	     *
	     * @return void
	     * @author Drew McLellan
	     */
	    public function add_new_item()
	    {
	        $new_item   = array(
				'itemID'       => $this->_get_next_item_id(),
				'collectionID' => $this->id(),
				'itemRev'      => 1,
				'itemJSON'     => '',
				'itemSearch'   => '',
	        );
	        
	       	$sortField = $this->get_option('sortField');

	        if ($sortField && $sortField!='') {
	        	$new_item['itemOrder'] = 1;
	        } else {
	        	if ($this->get_option('addToTop')==true) {
		            $new_item['itemOrder'] = $this->get_lowest_item_order()-1;
		        }else{
		            $new_item['itemOrder'] = $this->get_highest_item_order()+1;
		        }	
	        }

	        $Items = new PerchContent_CollectionItems();
	        $Item = $Items->create($new_item);

	        if ($sortField && $sortField!='') {
	        	$this->sort_items();
	        }
	        
	        $Perch = Perch::fetch();
	        $Perch->event('collection.add_item', $this);

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
	        $Items = new PerchContent_CollectionItems();
	        $Item = $Items->find_item($this->id(), $itemID);      
	        
	        $Item->delete();

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
	        $Items = new PerchContent_CollectionItems();
	        $Items->truncate_for_collection($this->id(), $resulting_item_count);

	        $Perch = Perch::fetch();
	        $Perch->event('collection.truncate', $this);
	    }

	    /**
	     * Empty the collection of all content. Used by import scripts primarily.
	     */
	    public function delete_all_items()
	    {
	    	$Items = new PerchContent_CollectionItems();
	    	$Items->delete_for_collection($this->id());
	    }
	    

	    
	    /**
	     * Duplicate all content items to create a new revision
	     *
	     * @return void
	     * @author Drew McLellan
	     */
	    public function create_new_revision($Item, $copy_resources=true)
	    {
	        $old_rev = (int) $Item->get_latest_rev();
	        $new_rev = $old_rev+1;
	        
	        $new_id = $Item->create_new_revision($old_rev, $new_rev, $copy_resources);	        
	        
	        $data = array();
	        $data['itemLatestRev'] = $new_rev;
	        
	        // if this is a new region
	        if ($new_rev==1) {
	            $data['itemRev'] = $new_rev;
	        }
	        
	        $Item->update_revision($data);

	        $Items = new PerchContent_CollectionItems();
	        $Items->delete_old_revisions($Item->itemID(), $this->history_items);

	        $Perch = Perch::fetch();
	        $Perch->event('collection.create_item_revision', $this);
	        
	        return $new_rev;
	    }
	    
	    /**
	     * Reorder the items in the region based on the sortField option.
	     *
	     * @return void
	     * @author Drew McLellan
	     */
	    public function sort_items($updatedItemID=false)
	    {
	        $sortField = $this->get_option('sortField');
	        
	        // Sort order
	        if ($sortField && $sortField!='') {
	            
	            $sortOrder = $this->get_option('sortOrder');
	            
	            $desc = false;
	            if ($sortOrder && strtoupper($sortOrder)=='DESC') {
	                $desc = true;
	            }
	            
	            $Items = new PerchContent_CollectionItems();
	            
	            $Items->sort_for_collection($this->id(), $sortField, $desc, $updatedItemID);        
	        }
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


	        $Items = new PerchContent_CollectionItems();
	        $vars  = $Items->get_flat_for_region($this->id(), $rev, false, $limit);
	        
	        $Template = new PerchTemplate('content/'.$this->collectionTemplate(), 'content');
	        
	        return $Template->render_group($vars, true);
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

	            $Items = new PerchContent_CollectionItems();
	            $Items->delete_revisions_newer_than($this->id(), $rev);
	        }

	        
	        $Perch = Perch::fetch();
	        $Perch->event('region.rollback', $this);

	        return true;
	    }
	    
	    public function get_lowest_item_order()
	    {
	        $Items = new PerchContent_CollectionItems();
	        return $Items->get_order_bound($this->id(), true);
	    }
	    
	    public function get_highest_item_order()
	    {
	        $Items = new PerchContent_CollectionItems();
	        return $Items->get_order_bound($this->id(), false);
	    }


	    public function clean_up_resources()
	    {
	        $subquery = 'SELECT resourceID FROM '.PERCH_DB_PREFIX.'resource_log WHERE appID='.$this->db->pdb('collections');

	        $Resources = new PerchResources;
	        $resources = $Resources->get_not_in_subquery('collections', $subquery);

	        if (PerchUtil::count($resources)) {
	            foreach($resources as $Resource) {
	                if ($Resource->is_not_in_use()) {
                    	$Resource->delete();    
                	}
	            }
	        }

	        $Perch = Perch::fetch();
	        $Perch->event('collection.cleanup', $this);
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

	            $Template = new PerchTemplate('content/'.$this->collectionTemplate(), 'content');

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
	        $Template = new PerchTemplate('content/'.$this->collectionTemplate(), 'content');
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

	    /**
	     * Import the items from a Region into the collection. Leave the Region untouched.
	     * @param  PerchContent_Region $Region [description]
	     * @return [type]                      [description]
	     */
	    public function import_from_region(PerchContent_Region $Region)
	    {
	    	$items = $Region->get_items();

	    	if (PerchUtil::count($items)) {

	    		$Resources = new PerchResources;

	    		if (!$this->current_userID) {
	    			$this->current_userID = 0;
	    		}
 
	    		foreach($items as $RegionItem) {

	    			$CollectionItem = $this->add_new_item();

	    			$data = PerchUtil::json_safe_decode($RegionItem->itemJSON(), true);
	    			$data['_id'] = $CollectionItem->itemID();
	    			$json = PerchUtil::json_safe_encode($data);

	    			$CollectionItem->update([
						'itemJSON'      => $json,
						'itemSearch'    => $RegionItem->itemSearch(),
						'itemUpdatedBy' => $this->current_userID,
	    				]);

	    			$CollectionItem->publish();
	    			$CollectionItem->index();

	    			if ($CollectionItem->ready_to_log_resources()) {
	                	$resourceIDs = $RegionItem->get_logged_resource_ids();
	                	if (PerchUtil::count($resourceIDs)) {
	                	    $CollectionItem->log_resources($resourceIDs);
	                	}else{
	                		PerchUtil::debug('No resources to log', 'error');
	                	}
	                }

	    		}
	    	}

	    }

	    
	    private function _get_next_item_id()
	    {
	        $Items = new PerchContent_CollectionItems();
	        return $Items->get_next_id();
	    }

}
