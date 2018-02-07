<?php

class PerchContent_CollectionItem extends PerchBase
{
    protected $table  = 'collection_items';
    protected $pk     = 'itemRowID';
    
    public $latest_rev;
    public $rev;     

    public function delete()
    {
    	$sql = 'DELETE FROM '.PERCH_DB_PREFIX.'collection_index WHERE itemID='.$this->db->pdb((int)$this->itemID());
    	$this->db->execute($sql);

        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'collection_revisions WHERE itemID='.$this->db->pdb((int)$this->itemID());
        $this->db->execute($sql);

        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'collection_items WHERE itemID='.$this->db->pdb((int)$this->itemID());
        $this->db->execute($sql);

        $Perch = Perch::fetch();
        $Perch->event('item.delete', $this);

    	parent::delete();
    }


	public function clear_resources()
	{
        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'resource_log WHERE appID='.$this->db->pdb('collections').' AND itemFK='.$this->db->pdb('itemRowID').' AND itemRowID='.$this->db->pdb((int)$this->itemRowID());
		$this->db->execute($sql);

        $Perch = Perch::fetch();
        $Perch->event('item.clear_resources', $this);
	}

    public function log_resources($resourceIDs)
    {
    	if (PerchUtil::count($resourceIDs)) {
    		$sql = 'INSERT IGNORE INTO '.PERCH_DB_PREFIX.'resource_log(`appID`, `itemFK`, `itemRowID`, `resourceID`) VALUES';
    		
    		$vals = array();

    		foreach($resourceIDs as $id) {
    			$vals[] = '('."'collections','itemRowID',".(int)$this->itemRowID().','.(int)$id.')';
    		}

    		$sql .= implode(',', $vals);

    		$this->db->execute($sql);

            $Perch = Perch::fetch();
            $Perch->event('item.log_resources', $this);
    	}
    }

    /**
     * Duplicate all the item with a new revision number
     *
     * @param string $old_rev 
     * @param string $new_rev 
     * @param boolean $copy_resources 
     * @return void
     * @author Drew McLellan
     */
    public function create_new_revision($old_rev, $new_rev, $copy_resources=false)
    {

        $Users          = new PerchUsers;
        $CurrentUser    = $Users->get_current_user();

        if ($CurrentUser) {
            $userID = $CurrentUser->id();
        } else {
            $userID = 0;
        }

        $sql = 'INSERT INTO '.$this->table.' (itemID, collectionID, itemRev, itemJSON, itemSearch, itemUpdatedBy)
                    SELECT itemID, collectionID, '.$this->db->pdb((int)$new_rev).' AS itemRev, itemJSON, itemSearch, '.$this->db->pdb($userID).' AS itemUpdatedBy
                    FROM '.$this->table.'
                    WHERE collectionID='.$this->db->pdb((int)$this->collectionID()).' AND itemID='.$this->db->pdb((int)$this->itemID()).' AND itemRev='.$this->db->pdb((int)$old_rev).'
                    ';
        $new_id = $this->db->execute($sql);


        if ($copy_resources) {
            $sql = 'REPLACE INTO '.PERCH_DB_PREFIX.'resource_log (appID, itemFK, itemRowID, resourceID)
                    SELECT cr.appID, cr.itemFK, c2.itemRowID, cr.resourceID 
                    FROM '.PERCH_DB_PREFIX.'resource_log cr, '.PERCH_DB_PREFIX.'collection_items c1, '.PERCH_DB_PREFIX.'collection_items c2
                    WHERE  cr.appID='.$this->db->pdb('collections').' AND cr.itemFK='.$this->db->pdb('itemRowID').' AND cr.itemRowID=c1.itemRowID AND c1.itemID = c2.itemID AND c1.collectionID='.$this->db->pdb((int)$this->collectionID()).' AND c2.collectionID='.$this->db->pdb((int)$this->collectionID()).'
                       AND c1.itemRev = '.$this->db->pdb((int)$old_rev).'
                       AND c2.itemRev = '.$this->db->pdb((int)$new_rev);
            $this->db->execute($sql);
        }
        
        $this->renumber_items($new_rev);

        return $new_id;
    }

    /**
     * Renumber the sort order for items, to keep them tidy.
     *
     * @param string $rev 
     * @return void
     * @author Drew McLellan
     */
    public function renumber_items($rev)
    {
        return true;

        $sql = 'SELECT itemRowID FROM '.$this->table.'
                WHERE collectionID='.$this->db->pdb((int)$this->collectionID()).' AND itemID='.$this->db->pdb((int)$this->itemID()).' AND itemRev='.$this->db->pdb((int)$rev).'
                ORDER BY itemOrder ASC';
        $rows = $this->db->get_rows($sql);
               
        
        if (PerchUtil::count($rows)) {
            $i = 0;
            foreach($rows as $row) {
                $data = array();
                $data['itemOrder'] = 1000 + $i;
                $this->db->update($this->table, $data, 'itemRowID', $row['itemRowID']);
                $i++;
            }
        }
    }

    /**
     * An undo
     *
     * @return void
     * @author Drew McLellan
     */
    public function revert_most_recent()
    {
        $undo_rev = $this->get_latest_rev();
        
        $Items = new PerchContent_CollectionItems();
        $prev_rev = $this->get_previous_revision_number($undo_rev);
        
        if ($prev_rev) {
            $this->publish($prev_rev);
        
            $Items->delete_revision($this->itemID(), $undo_rev);

            $Perch = Perch::fetch();
            $Perch->event('collection.undo_item', $this);
            
            PerchUtil::mark('/undo 1');

            return true;
        }
        
        return false;
    }

    /**
     * Make current revision non-draft.
     *
     * @return void
     * @author Drew McLellan
     */
    public function publish($rev=false, $change_latest=true)
    {
        if ($rev===false) $rev = $this->get_latest_rev();
               
        $data = array();
        $data['itemRev']       = $rev;
        
        if ($change_latest) {
            $data['itemLatestRev'] = $rev;
        }
        
        $this->update_revision($data);

        $Perch = Perch::fetch();
        $Perch->event('collection.publish_item', $this);
    }

    /**
     * Update the item's row in the collection_revisions table
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function update_revision($data)
    {
        $this->db->update(PERCH_DB_PREFIX.'collection_revisions', $data, 'itemID', $this->itemID());
        $this->latest_rev = false;
        $this->rev = false;
    }

    /**
     * Get the previous stored revision number for the given item
     *
     * @param string $rev 
     * @return void
     * @author Drew McLellan
     */
    public function get_previous_revision_number($rev)
    {
        $sql = 'SELECT itemRev FROM '.$this->table.'
                WHERE itemID='.$this->db->pdb((int)$this->itemID()).' AND itemRev<'.(int)$rev.'
                ORDER BY itemRev DESC LIMIT 1';
        return $this->db->get_value($sql);
    }
    

    /**
     * Get the number of the latest revision of this item
     * @return [type] [description]
     */
    public function get_latest_rev()
    {
        if ($this->latest_rev) return $this->latest_rev;
        $this->_cache_revision_numbers();
        return $this->latest_rev;
    }

    /**
     * Get the number of the current revision of this item
     * @return [type] [description]
     */
    public function get_rev()
    {
        if ($this->rev) return $this->rev;
        $this->_cache_revision_numbers();
        return $this->rev;
    }

    /**
     * Get the revision number of the oldest revision we currently have.
     * @param  [type] $itemID [description]
     * @return [type]           [description]
     */
    public function get_oldest_rev($itemID)
    {
        $sql = 'SELECT MIN(itemRev) FROM '.$this->table.'
                WHERE itemID='.$this->db->pdb((int)$itemID);
        return $this->db->get_value($sql);
    }

    

    /**
     * Add the content of this region into the content index
     * @param  boolean $item_id [description]
     * @param  boolean $rev [description]
     * @return [type]       [description]
     */
    public function index($rev=false)
    {
        $this->latest_rev = false;
        $this->rev = false;

        if ($rev===false) {
            if ($this->has_draft()) {
                PerchUtil::debug('Indexing draft');
                $rev = $this->get_latest_rev();
                $rev_type = 'latest';
            }else{
                PerchUtil::debug('Indexing published');
                $rev = $this->get_rev();
                $rev_type = false;
            }
        }

        $Collections = new PerchContent_Collections();
        $Collection = $Collections->find($this->collectionID());
        $Items = new PerchContent_CollectionItems();

        // clear out old items
        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'collection_index 
                WHERE itemID='.$this->db->pdb((int)$this->itemID()).' AND itemRev<'.$this->db->pdb((int)$this->get_oldest_rev($this->itemID()));
        $this->db->execute($sql);

        $items  = $Items->get_for_collection($this->collectionID(), $rev_type, $this->itemID());

        if (PerchUtil::count($items)) {

            $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'collection_index 
                    WHERE itemID='.$this->db->pdb((int)$this->itemID()).' AND itemRev='.$this->db->pdb((int)$rev);
            $this->db->execute($sql);

            $Template = new PerchTemplate('content/'.$Collection->collectionTemplate(), 'content');
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
                
                $sql = 'INSERT INTO '.PERCH_DB_PREFIX.'collection_index (itemID, collectionID, itemRev, indexKey, indexValue) VALUES ';
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
                                    $data['itemID']       = (int) $Item->itemID();
                                    $data['collectionID'] = (int) $this->collectionID();
                                    $data['itemRev']      = (int) $rev;
                                    $data['indexKey']     = $this->db->pdb(substr($index_item['key'], 0, 64));
                                    $data['indexValue']   = $this->db->pdb(substr($index_item['value'], 0, 255));

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
                    $data['itemID']       = (int) $Item->itemID();
                    $data['collectionID'] = (int) $this->collectionID();
                    $data['itemRev']      = (int) $rev;
                    $data['indexKey']     = $this->db->pdb('_id');
                    $data['indexValue']   = (int) $Item->itemID();

                    $values[] = '('.implode(',', $data).')';
                } 
                
                
                // natural order
                $data = array();
                $data['itemID']       = (int) $Item->itemID();
                $data['collectionID'] = (int) $this->collectionID();
                $data['itemRev']      = (int) $rev;
                $data['indexKey']     = $this->db->pdb('_order');
                $data['indexValue']   = $this->db->pdb($Item->get_item_order());

                $values[] = '('.implode(',', $data).')';


                // date order
                $data = array();
                $data['itemID']       = (int) $Item->itemID();
                $data['collectionID'] = (int) $this->collectionID();
                $data['itemRev']      = (int) $rev;
                $data['indexKey']     = $this->db->pdb('_date');
                $data['indexValue']   = $this->db->pdb($Item->get_created_date());

                $values[] = '('.implode(',', $data).')';



                $sql .= implode(',', $values);
                $this->db->execute($sql);

            }    
        

        }

        // optimize index 
        $sql = 'OPTIMIZE TABLE '.PERCH_DB_PREFIX.'collection_index';
        $this->db->get_row($sql);
        

        $Perch = Perch::fetch();
        $Perch->event('region.index', $this);
    }


    public function get_created_date()
    {
        $sql = 'SELECT itemCreated FROM '.PERCH_DB_PREFIX.'collection_revisions WHERE itemID='.$this->db->pdb((int)$this->itemID());
        return $this->db->get_value($sql);
    }

    public function get_item_order()
    {
        $sql = 'SELECT itemOrder FROM '.PERCH_DB_PREFIX.'collection_revisions WHERE itemID='.$this->db->pdb((int)$this->itemID());
        return $this->db->get_value($sql);
    }

    /**
     * Does the item have a newer draft than the published version?
     *
     * @return void
     * @author Drew McLellan
     */
    public function has_draft()
    {
        return ((int)$this->get_latest_rev() > (int)$this->get_rev());
    }

    /**
     * Are there items in the history stack to undo?
     *
     * @return void
     * @author Drew McLellan
     */
    public function is_undoable()
    {
        // A collection is always undoable. Fancy that.
        return true;
    }

    /**
     * Get the value of a specific field from the JSON
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function get_field($key)
    {
        $data = PerchUtil::json_safe_decode($this->itemJSON(), true);
        if (isset($data[$key])) {
            return $data[$key];
        }
        return false;
    }


    public function to_array()
    {
        $out = $this->details;

        $dynamic_field_col = 'itemJSON';
        if (isset($out[$dynamic_field_col]) && $out[$dynamic_field_col] != '') {
            $dynamic_fields = PerchUtil::json_safe_decode($out[$dynamic_field_col], true);
            if (PerchUtil::count($dynamic_fields)) {
                foreach($dynamic_fields as $key=>$value) {
                    $out['perch_'.$key] = $value;
                }
                $out = array_merge($dynamic_fields, $out);
            }
        }

        return $out;
    }


    /**
     * Look up the item revisions and cache them
     * @return nothing
     */
    private function _cache_revision_numbers()
    {
        $sql = 'SELECT itemRev, itemLatestRev FROM '.PERCH_DB_PREFIX.'collection_revisions 
                WHERE collectionID='.$this->db->pdb((int)$this->collectionID()).' AND itemID='.$this->db->pdb((int)$this->itemID()).' LIMIT 1';
        $row = $this->db->get_row($sql);

        if (PerchUtil::count($row)) {
            $this->latest_rev = (int)$row['itemLatestRev'];
            $this->rev        = (int)$row['itemRev'];
        }
    }

}
