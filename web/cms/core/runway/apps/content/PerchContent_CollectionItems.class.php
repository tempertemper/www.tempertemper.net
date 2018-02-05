<?php

class PerchContent_CollectionItems extends PerchFactory
{
    protected $singular_classname = 'PerchContent_CollectionItem';
    protected $table    = 'collection_items';
    protected $pk   = 'itemRowID';

    protected $default_sort_column  = 'itemCreated';  


    public function create($data)
    {
        if (isset($data['itemOrder'])) {
            $itemOrder = $data['itemOrder'];
            unset($data['itemOrder']);
        }else{
            $itemOrder = 1000;
        }

        $Users          = new PerchUsers;
        $CurrentUser    = $Users->get_current_user();

        if ($CurrentUser->id()) {
            $userID = $CurrentUser->id();
        } else {
            $userID = 0;
        }

        $data['itemUpdatedBy'] = $userID;

        $Item = parent::create($data);

        if ($Item) {
            $this->db->insert(PERCH_DB_PREFIX.'collection_revisions', [
                'collectionID'  => $Item->collectionID(),
                'itemID'        => $Item->itemID(),
                'itemOrder'     => $itemOrder,
                'itemRev'       => $Item->itemRev(),
                'itemLatestRev' => $Item->itemRev(),
                'itemCreated'   => date('Y-m-d H:i:s'),
                'itemCreatedBy' => $userID,
            ]);
        }

        return $Item;
    }

    
    /**
     * Find an item by collection, ID and revision
     *
     * @param string $collectionID 
     * @param string $itemID 
     * @param string $rev 
     * @return void
     * @author Drew McLellan
     */
    public function find_item($collectionID, $itemID, $rev=false)
    {

        $sql = 'SELECT * FROM '.$this->table.' ci, '.PERCH_DB_PREFIX.'collection_revisions r
                WHERE r.itemID=ci.itemID AND ci.collectionID='.$this->db->pdb((int)$collectionID).' AND ci.itemRev=r.itemRev AND ci.itemID='.$this->db->pdb((int)$itemID);
        
        $row =  $this->db->get_row($sql);
        
        return $this->return_instance($row);
    }

    public function find_next_item($Item)
    {

        $sql = 'SELECT * FROM '.$this->table.' ci, '.PERCH_DB_PREFIX.'collection_revisions r
                WHERE r.itemID=ci.itemID AND ci.collectionID='.$this->db->pdb((int)$Item->collectionID()).' AND ci.itemRev=r.itemRev AND r.itemOrder > '.$this->db->pdb((int)$Item->itemOrder()).'
                ORDER BY r.itemOrder
                LIMIT 1';
        
        $row =  $this->db->get_row($sql);
        
        return $this->return_instance($row);
    }

    public function find_previous_item($Item)
    {
        $sql = 'SELECT * FROM '.$this->table.' ci, '.PERCH_DB_PREFIX.'collection_revisions r
                WHERE r.itemID=ci.itemID AND ci.collectionID='.$this->db->pdb((int)$Item->collectionID()).' AND ci.itemRev=r.itemRev AND r.itemOrder < '.$this->db->pdb((int)$Item->itemOrder()).'
                ORDER BY r.itemOrder DESC
                LIMIT 1';   
        $row =  $this->db->get_row($sql);
        
        return $this->return_instance($row);
    }

    /**
     * Find an item's revision row
     *
     * @param string $collectionID 
     * @param string $itemID 
     * @param string $rev 
     * @return void
     * @author Drew McLellan
     */
    public function find_item_revision($itemID)
    {
        $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'collection_revisions r WHERE itemID='.$this->db->pdb($itemID);
        
        $row =  $this->db->get_row($sql);
        
        return $this->return_instance($row);
    }



    /**
     * Get a flat array of the items (or single item) in a collection, for the edit form
     *
     * @param string $collectionID 
     * @param string $rev 
     * @param string $item_id 
     * @return void
     * @author Drew McLellan
     */
    public function get_flat_for_collection($collectionID, $rev=false, $item_id=false, $limit_or_Paging=false, $limit_fields_to=false, PerchTemplate $Template = null)
    {

        $sort_val = null;
        $sort_dir = null;

        if ($limit_or_Paging && is_object($limit_or_Paging)) {
            $Paging = $limit_or_Paging;
            $limit = false;
        }else{
            $Paging = false;
            $limit = $limit_or_Paging;
        }

        if ($Paging) {
            $sql = $Paging->select_sql();
            list($sort_val, $sort_dir) = $Paging->get_custom_sort_options($Template);
        }else{
            $sql = 'SELECT';
        }

        $rev_field = 'itemRev';

        if ($rev=='latest') {
            $rev_field = 'itemLatestRev';
        }


        $sql .= ' ci.*, r.itemOrder, IF(r.itemLatestRev>r.itemRev,1,0) AS _has_draft';

        if ($sort_val) {
            $sql .= ', idx.indexValue AS sortval';
        }

        $sql .= ' FROM '.$this->table.' ci, '.PERCH_DB_PREFIX.'collection_revisions r';

        if ($sort_val) {
            $sql .= ', '.PERCH_DB_PREFIX.'collection_index idx';
        }


        $sql .= ' WHERE r.itemID=ci.itemID AND ci.collectionID='.$this->db->pdb((int)$collectionID).' AND ci.itemRev=r.'.$rev_field;
                
        if ($item_id!==false) {
            $sql .= ' AND ci.itemID='.$this->db->pdb($item_id);
        }

        if ($sort_val) {
            $sql .= ' AND ci.itemID=idx.itemID AND ci.itemRev=idx.itemRev AND ci.collectionID=idx.collectionID
                        AND idx.indexKey='.$this->db->pdb($sort_val).' 
                    ORDER BY sortval '.$sort_dir.' ';
        } else {
            $sql .= ' ORDER BY r.itemOrder ASC ';    
        }
        
    
        if ($Paging) {
            $sql .= $Paging->limit_sql();
        }else{
            if ($limit!==false) {
                $sql .= ' LIMIT '.intval($limit);
            }
        }
       
        
        $rows =  $this->db->get_rows($sql);

        if ($Paging) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }
        
        if (PerchUtil::count($rows)) {
            foreach($rows as &$row) {
                $fields = PerchUtil::json_safe_decode($row['itemJSON'], true);
                if (is_array($fields)) {
                    if ($limit_fields_to && isset($fields[$limit_fields_to])) {
                        $row = array_merge(array($limit_fields_to=>$fields[$limit_fields_to]), $row);
                    }else{
                        $row = array_merge($fields, $row);    
                    }
                    
                }
            }
        }
    
        return $rows;
    }
    

    /**
     * Get a flat array of the items (or single item) in a collection, for the edit form
     *
     * @param string $collectionID 
     * @param string $rev 
     * @param string $item_id 
     * @return void
     * @author Drew McLellan
     */
    public function get_flat_for_sorting($collectionID, $sort_field, $desc=false)
    {
        
        $sql = 'SELECT ci.itemRowID, ci.itemID, ci.itemJSON, r.itemOrder 
                FROM '.$this->table.' ci, '.PERCH_DB_PREFIX.'collection_revisions r 
                WHERE r.itemID=ci.itemID AND ci.collectionID='.$this->db->pdb((int)$collectionID).' AND ci.itemRev=r.itemRev
                ORDER BY r.itemOrder '.($desc ? 'DESC' : 'ASC');
       
        $rows =  $this->db->get_rows($sql);
       
        if (PerchUtil::count($rows)) {
            foreach($rows as &$row) {
                $fields = PerchUtil::json_safe_decode($row['itemJSON'], true);
                if (is_array($fields)) {
                    if ($sort_field && isset($fields[$sort_field])) {
                        $row = array_merge(array($sort_field=>$fields[$sort_field]), $row);
                    }else{
                        $row = array_merge($fields, $row);    
                    }
                }
                unset($fields);
            }
        }
    
        return $rows;
    }

    /**
     * Get a items (or single item) in a collection, for the edit form update process
     *
     * @param string $collectionID 
     * @param string $rev 
     * @param string $item_id 
     * @return void
     * @author Drew McLellan
     */
    public function get_for_collection($collectionID, $rev=false, $item_id=false, $custom_order='')
    {
        $sql = 'SELECT * FROM '.$this->table.' ci, '.PERCH_DB_PREFIX.'collection_revisions r
                WHERE r.itemID=ci.itemID AND ci.collectionID='.$this->db->pdb((int)$collectionID);

        if ($rev=='latest') {
            $sql .= ' AND ci.itemRev=r.itemLatestRev';
        }else{
            $sql .= ' AND ci.itemRev=r.itemRev';
        }
            
                
        if ($item_id!==false) {
            $sql .= ' AND ci.itemID='.$this->db->pdb($item_id);
        }
        
        $sql .= ' ORDER BY '.$custom_order.'r.itemOrder ASC';
        
        return $this->return_instances($this->db->get_rows($sql));
    }
    
    /**
     * Get items specified by IDs - this is used for populating templates at runtime.
     * @param  [type] $collectionID [description]
     * @param  [type] $item_ids     [description]
     * @return [type]               [description]
     */
    public function get_for_collection_by_ids_runtime($collectionID, $item_ids, $sort=false, $count=false)
    {
        $sql = 'SELECT * FROM '.$this->table.' ci, '.PERCH_DB_PREFIX.'collection_revisions r
                WHERE r.itemID=ci.itemID AND ci.collectionID='.$this->db->pdb((int)$collectionID) .'
                AND ci.itemRev=r.itemRev ';

        if (PerchUtil::count($item_ids)==1) {
            $sql .= 'AND ci.itemID='.$this->db->pdb((int)$item_ids[0]).' ';
        }else{
            $sql .= 'AND ci.itemID IN ('.$this->db->implode_for_sql_in($item_ids, true).') ORDER BY ';

            if ($sort && $sort == 'custom') {
                $sql .= 'CASE r.itemID ';
                for ($i=0; $i<count($item_ids); $i++) {
                    $sql .= ' WHEN '. $item_ids[$i] .' THEN '.($i+1);
                }
                $sql .= ' ELSE ' .($i+1). ' END, ';
            }

            $sql .= 'r.itemOrder ASC';    

            if ($count) {
                $sql .= ' LIMIT '.(int)$count;
            }
        }
        
        return $this->return_instances($this->db->get_rows($sql));
    }

	
	/**
	 * Get a count of the number of items in the collection for the latest revision
	 *
	 * @param string $collectionID 
	 * @param string $rev 
	 * @return void
	 * @author Drew McLellan
	 */
	public function get_count_for_collection($collectionID)
	{
		$sql = 'SELECT COUNT(*) FROM '.PERCH_DB_PREFIX.'collection_revisions r, '.$this->table.' ci
                WHERE r.collectionID=ci.collectionID AND r.itemID=ci.itemID AND r.itemLatestRev=ci.itemRev AND ci.itemJSON!=\'\' AND r.collectionID='.$this->db->pdb((int)$collectionID);
        return $this->db->get_count($sql);
	}

    /**
     * Get a list of item revisions for the given item - used by Revision History
     * @param  [type] $collectionID [description]
     * @return [type]           [description]
     */
    public function get_revisions_for_item($collectionID, $itemID)
    {
        $sql = 'SELECT itemRev, itemUpdated, itemUpdatedBy FROM '.$this->table.'
                WHERE collectionID='.$this->db->pdb((int)$collectionID).' AND itemID='.$this->db->pdb($itemID).'
                GROUP BY itemRev
                ORDER BY itemRev DESC';
        return $this->db->get_rows($sql);
    }




    /**
     * Purge old revisions, leaving only the 'number_remaining' count of revisions for that region
     * @param  [type] $itemID         [description]
     * @param  [type] $number_remaining [description]
     * @return [type]                   [description]
     */
    public function delete_old_revisions($itemID, $number_remaining)
    {
        $sql = 'SELECT itemRev FROM '.PERCH_DB_PREFIX.'collection_revisions WHERE itemID='.$this->db->pdb($itemID);
        $live_rev = $this->db->get_value($sql);

        $sql = 'DELETE FROM '.$this->table.' WHERE itemID='.$this->db->pdb($itemID).' AND itemRev!='.$live_rev.' AND itemRev IN 
                    (SELECT itemRev FROM (SELECT DISTINCT itemRev FROM '.$this->table.' 
                                            WHERE itemID='.$this->db->pdb($itemID).'
                                            ORDER BY itemRev DESC
                                            LIMIT '.$number_remaining.', 99999) AS t2)';
        
        $this->db->execute($sql);

        // delete references to resources
        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'resource_log WHERE appID='.$this->db->pdb('collections').' AND itemFK='.$this->db->pdb('itemRowID').' AND itemRowID NOT IN 
                    (SELECT itemRowID FROM '.$this->table.')';
        $this->db->execute($sql);
    }
    
    
    /**
     * Get the highest or lowest item order index for the region. Default is highest.
     *
     * @param string $collectionID 
     * @param string $rev 
     * @param string $lowest 
     * @return void
     * @author Drew McLellan
     */
    public function get_order_bound($collectionID, $lowest=false)
    {
        $sql = 'SELECT itemOrder FROM '.PERCH_DB_PREFIX.'collection_revisions
                WHERE collectionID='.$this->db->pdb((int)$collectionID);
                
        if ($lowest) {
            $sql .= ' ORDER BY itemOrder ASC ';
        }else{
            $sql .= ' ORDER BY itemOrder DESC ';
        }
        
        $sql .= ' LIMIT 1 ';
        
        $val = (int) $this->db->get_value($sql);
        
        if ($val==0) $val = 999;
        
        return $val;
    }
    
    /**
     * Reorder the items within a collection by the given field
     *
     * @param string $collectionID 
     * @param string $field 
     * @param string $desc 
     * @return void
     * @author Drew McLellan
     */
    public function sort_for_collection($collectionID, $field, $desc=false, $updatedItemId=false)
    {
        PerchUtil::debug('Sorting collection '.$collectionID.' by '.$field.' '. ($desc ? 'DESC' : 'ASC'), 'notice');

        $items = $this->get_flat_for_sorting($collectionID, $field, $desc);
        
        if (PerchUtil::count($items)) {
            
            $sorted = PerchUtil::array_sort($items, $field, $desc);
            
            $i = 1000;
            
            if ($updatedItemId) {

                // Search and renumber more selectively
                
                $sql = 'SELECT itemOrder FROM ' .PERCH_DB_PREFIX.'collection_revisions WHERE itemID='.$this->db->pdb((int)$updatedItemId);
                $original_order = (int)$this->db->get_value($sql);
                
                
                foreach($sorted as $item) {

                    if ($item['itemID']==$updatedItemId) {

                        // has the position changed?

                        if ($original_order!=$i) {

                            // is the new position higher or lower in the list?
                            if ($i>$original_order) {
                                // the item has been moved down the list
                                 
                                // Update all with a lesser order
                                $sql = 'UPDATE '.PERCH_DB_PREFIX.'collection_revisions SET itemOrder=itemOrder-1 
                                            WHERE itemID!='.$this->db->pdb((int)$item['itemID']).' AND itemOrder>='.$original_order.' AND itemOrder<='.$i;
                                $this->db->execute($sql);
                                
                            }else{
                                // the item has been moved up the list
                                
                                // Update all with a greater order
                                $sql = 'UPDATE '.PERCH_DB_PREFIX.'collection_revisions SET itemOrder=itemOrder+1 WHERE itemID!='.$this->db->pdb((int)$item['itemID']).' AND itemOrder>='.$i;
                                $this->db->execute($sql);
                            }

                            
                            // Update this item to new location
                            $sql = 'UPDATE '.PERCH_DB_PREFIX.'collection_revisions SET itemOrder='.$i.' WHERE itemID='.$this->db->pdb((int)$item['itemID']).'';
                            $this->db->execute($sql);

                        }
                    }
                 
                    $i++;
                }


            }else{

                // Renumber all of them
                foreach($sorted as $item) {
                    $sql = 'UPDATE '.PERCH_DB_PREFIX.'collection_revisions SET itemOrder='.$i.' WHERE itemID='.$this->db->pdb((int)$item['itemID']).'';
                    $this->db->execute($sql);
                    $i++;
                }    
            }

            
            
            return true;
        }
        
        return false;
    }
    

    
    /**
     * Delete all the items for the given region and revision
     *
     * @param string $itemID 
     * @param string $rev 
     * @return void
     * @author Drew McLellan
     */
    public function delete_revision($itemID, $rev)
    {

        $sql = 'DELETE FROM '.$this->table.'
                WHERE itemID='.$this->db->pdb($itemID).' AND itemRev='.(int)$rev;
        return $this->db->execute($sql);
    }

    /**
     * When performing a rollback, we need to delete the newer revisions
     * @param  [type] $collectionID [description]
     * @param  [type] $rev      [description]
     * @return [type]           [description]
     */
    public function delete_revisions_newer_than($collectionID, $rev)
    {
        $sql = 'DELETE FROM '.$this->table.'
                WHERE collectionID='.$this->db->pdb((int)$collectionID).' AND itemRev>'.(int)$rev;
        return $this->db->execute($sql);
    }
    
    /**
     * Remove all but x items from the region.
     *
     * @param string $collectionID 
     * @param string $rev 
     * @param string $resulting_item_count 
     * @return void
     * @author Drew McLellan
     */
    public function truncate_for_collection($collectionID, $rev, $resulting_item_count=1)
    {
        $sql = 'DELETE FROM '.$this->table.'
                WHERE itemRowID IN 
                    (SELECT itemRowID FROM 
                        (SELECT itemRowID FROM '.$this->table.'
                        WHERE collectionID='.$this->db->pdb((int)$collectionID).' AND itemRev='.(int)$rev.'
                        ORDER BY itemOrder ASC
                        LIMIT '.$resulting_item_count.', 99999999 
                        ) AS t2
                    )';
        return $this->db->execute($sql);
    }

    /**
     * Deletes all items in the collection. Used mainly by bulk import scripts to reset. Not used commonly during editing.
     *
     * @param string $collectionID 
     */
    public function delete_for_collection($collectionID)
    {
        $sql = 'DELETE FROM '.$this->table.'
                WHERE collectionID='.$this->db->pdb((int)$collectionID);
        return $this->db->execute($sql);

        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'collection_revisions
                WHERE collectionID='.$this->db->pdb((int)$collectionID);
        return $this->db->execute($sql);

        $sql = 'DELETE FROM '.PERCH_DB_PREFIX.'collection_index
                WHERE collectionID='.$this->db->pdb((int)$collectionID);
        return $this->db->execute($sql);
    }
    
    
    /**
     * Get the next itemID
     *
     * @return void
     * @author Drew McLellan
     */
    public function get_next_id()
    {
        $sql = 'SELECT MAX(itemID)+1 FROM '.PERCH_DB_PREFIX.'collection_revisions';
        $r = $this->db->get_count($sql);
        
        if ($r==0) $r++;
        
        return $r;
    }



}
