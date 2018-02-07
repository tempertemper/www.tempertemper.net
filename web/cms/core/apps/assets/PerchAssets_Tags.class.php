<?php

class PerchAssets_Tags extends PerchFactory
{
    protected $table     = 'resource_tags';
	protected $pk        = 'tagID';
	protected $singular_classname = 'PerchAssets_Tag';
	
	protected $default_sort_column = 'tagCount';
	protected $default_sort_direction = 'DESC';
	
	/**
	 * fetch the tags for a given asset
	 * @param int $postID
	 */
	public function get_for_asset($resourceID)
	{
	    $sql = 'SELECT t.*
	            FROM '.$this->table.' t, '.PERCH_DB_PREFIX.'resources_to_tags r2t
	            WHERE t.tagID=r2t.tagID
	                AND r2t.resourceID='.$this->db->pdb($resourceID);
	    $rows   = $this->db->get_rows($sql);
	    
	    return $this->return_instances($rows);
	}

	public function get_for_asset_as_edit_string($resourceID)
	{
		$out = array();
		$tags = $this->get_for_asset($resourceID);
		if (PerchUtil::count($tags)) {
			foreach($tags as $Tag) {
				$out[] = $Tag->tagSlug();
			}
		}

		return implode(', ', $out);
	}

	public function assign_tag_string($resourceID, $tag_string, $replace_existing=true)
	{
		if ($replace_existing) $this->delete_for_asset($resourceID);
		
		$tag_ids = $this->parse_string_to_ids($tag_string);

		if (PerchUtil::count($tag_ids)) {
			foreach($tag_ids as $id) {
				$this->db->insert(PERCH_DB_PREFIX.'resources_to_tags', array(
					'resourceID'=>$resourceID,
					'tagID'=>$id,
					));
			}

			$this->update_counts();
		}
	}


	public function assign_tag_array($resourceID, $tag_array, $replace_existing=true)
	{
		if ($replace_existing) $this->delete_for_asset($resourceID);
		
		$tag_ids = array();
		if (PerchUtil::count($tag_array)) {
			foreach($tag_array as $tag) {
				$Tag = $this->find_or_create(PerchUtil::urlify($tag), $tag);
				if ($Tag) {
					$tag_ids[] = $Tag->id();
				}	
			}
		}

		if (PerchUtil::count($tag_ids)) {
			foreach($tag_ids as $id) {
				$this->db->insert(PERCH_DB_PREFIX.'resources_to_tags', array(
					'resourceID'=>$resourceID,
					'tagID'=>$id,
					));
			}

			$this->update_counts();
		}
	}

	public function delete_for_asset($resourceID)
	{
		$sql = 'DELETE FROM '.PERCH_DB_PREFIX.'resources_to_tags WHERE resourceID='.$this->db->pdb($resourceID);
		$this->db->execute($sql);
	}

	public function parse_string_to_ids($tag_string)
	{
		$tags = $this->_tag_parse($tag_string);
		PerchUtil::debug($tags);
		$ids  = array();

		if (PerchUtil::count($tags)) {
			foreach($tags as $tag) {
				$Tag = $this->find_or_create(PerchUtil::urlify($tag), $tag);
				if ($Tag) {
					$ids[] = $Tag->id();
				}	
			}
		}
		return $ids;
	}
	

	public function get_top($count) 
	{

		$sql = 'SELECT t.tagTitle, t.tagSlug, t.tagCount
            FROM '.PERCH_DB_PREFIX.'resource_tags t
            WHERE tagCount > 0
            GROUP BY t.tagID
            ORDER BY t.tagCount DESC
            LIMIT '.(int)$count;	
		
		$rows   = $this->db->get_rows($sql);

    	$r = $this->return_instances($rows);
    	    
    	return $r;
	}

	/**
	 * 
	 * retrieves all tags used by assets along with a count of number of posts for each tag.
	 */
	public function all_in_use($opts=array()) 
	{

		$sql = 'SELECT t.tagTitle, t.tagSlug, t.tagCount
            FROM '.PERCH_DB_PREFIX.'resource_tags t
            WHERE tagCount > 0
            GROUP BY t.tagID
            ORDER BY t.tagTitle ASC';	
		
		$rows   = $this->db->get_rows($sql);

    	$r = $this->return_instances($rows);
    	    
    	return $r;
	}

	public function find_or_create($slug, $title)
	{
		$sql = 'SELECT * FROM '.$this->table.' WHERE tagSlug='.$this->db->pdb($slug).' LIMIT 1';
		$row = $this->db->get_row($sql);

		if (PerchUtil::count($row)) {
			return $this->return_instance($row);
		}

		// Tag wasn't found, so create a new one and return it.
		
		$data = array();
		$data['tagSlug']  = $slug;
		$data['tagTitle'] = $title;
		$data['tagCount'] = 0;

		return $this->create($data);

	}

	/**
	 * Find a tag by its tagSlug
	 *
	 * @param string $slug 
	 * @return void
	 * @author Drew McLellan
	 */
	public function find_by_slug($slug)
    {
        $sql    = 'SELECT * 
                    FROM ' . $this->table . '
                    WHERE tagSlug='. $this->db->pdb($slug) .'
                    LIMIT 1';
                    
        $result = $this->db->get_row($sql);
        
        if (is_array($result)) {
            return new $this->singular_classname($result);
        }
        
        return false;
    }


	public function update_counts()
    {
    	$sql = 'SELECT t.tagID, COUNT(r2t.resourceID) AS qty
                FROM '.PERCH_DB_PREFIX.'resource_tags t, '.PERCH_DB_PREFIX.'resources_to_tags r2t
                WHERE r2t.tagID=t.tagID 
                GROUP BY t.tagID
                ORDER BY t.tagTitle ASC';
        $rows = $this->db->get_rows($sql);

        if (PerchUtil::count($rows)) {

            // reset counts to zero
            $sql = 'UPDATE '.PERCH_DB_PREFIX.'resource_tags SET tagCount=0';
            $this->db->execute($sql);

        	foreach($rows as $row) {
        		$sql = 'UPDATE '.PERCH_DB_PREFIX.'resource_tags SET tagCount='.$this->db->pdb($row['qty']).' WHERE tagID='.$this->db->pdb($row['tagID']).' LIMIT 1';
        		$this->db->execute($sql);
        	}
        }
    }

	public function async_search($term) 
	{

		$sql = 'SELECT t.tagID AS `id`, t.tagSlug AS `value`, t.tagTitle AS `label`
            FROM '.PERCH_DB_PREFIX.'resource_tags t
            WHERE tagSlug LIKE '.$this->db->pdb($term.'%').' OR tagSlug='.$this->db->pdb($term).'
            ORDER BY t.tagCount DESC';	
		
		$rows   = $this->db->get_rows($sql);

		return $rows;

	}


    private function _tag_parse($str)
	{
		$tags   = array();
		$i=0;
	
		//remove quoted segments
		$quoted = array();    
		preg_match_all('/\"(.*?)\"/', $str, $quoted);
		if (is_array($quoted[1])) {
			foreach ($quoted[1] as $tag) {			        
				$tag = trim($tag);
				if ($tag != '' && !in_array($tag, $tags)) {
					$tags[$i]=$tag;
					$str = preg_replace('/\"(.*?)\",?\s?/', '', $str);
					$i++;
				}
		    	}
		}
       	
       	//find comma separated 
		$commas=preg_split('/,/', $str);
        	if (is_array($commas) && count($commas) > 1) {
        		foreach ($commas as $tag) {
        		        $tag = trim($tag);
        		        if ($tag != '' && !in_array($tag, $tags)) {
					$tags[$i] = $tag;
					$str = preg_replace('/(.*?),(\s*?)/', '', $str);
					$i++;
				}
        		}
		}
		//if user hasn't delimited by commas, find space seperated	
        	else {
			$spaces=preg_split('/\s/', $str);
        		foreach ($spaces as $tag) {
        	       		$tag = trim($tag);
		                if ($tag != '' && !in_array($tag, $tags)) {
					$tags[$i] = $tag;
    					$str = preg_replace('/(.*?)/', '', $str);
				        $i++;
				}
		        }	
       		}         
		return $tags; 
	}

    
}
