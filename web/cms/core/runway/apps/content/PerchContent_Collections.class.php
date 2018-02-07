<?php 

class PerchContent_Collections extends PerchFactory
{
	protected $singular_classname  = 'PerchContent_Collection';
	protected $table               = 'collections';
	protected $pk                  = 'collectionID';
	
	protected $default_sort_column = 'collectionOrder';  

	public function get_by_id_string($id_string) 
	{
		$ids = explode(',', $id_string);
		if (PerchUtil::count($ids)) {
			foreach($ids as &$id) {
				$id = trim($id);
			}
			$sql = 'SELECT * FROM '.$this->table.' WHERE collectionID IN ('.$this->db->implode_for_sql_in($ids, true).')';
			return $this->return_instances($this->db->get_rows($sql));
		}

		return false;
	}

	public function get_data_from_ids_runtime($collectionKey, $vars, $sort=false, $count=false)
	{
		if (isset($this->cache[$collectionKey]) && $this->cache[$collectionKey]) {
			$collectionID = $this->cache[$collectionKey];
		}else{
			$Collection = $this->get_one_by('collectionKey', $collectionKey);
			if ($Collection) {
				$this->cache[$collectionKey] = $Collection->id();
				$collectionID = $Collection->id();	
			}
		}	

		if ($collectionID) {
			$Items = new PerchContent_CollectionItems();
			$items = $Items->get_for_collection_by_ids_runtime($collectionID, $vars, $sort, $count);
			if (PerchUtil::count($items)) {
				$out = [];
				foreach($items as $Item) {
					$out[] = $Item->to_array();
				}
				return $out;
			}
		}

		return false;
	}

	public function get_indexed_from_ids($collectionKey, $ids, $prefix)
	{
		$Collection = $this->get_one_by('collectionKey', $collectionKey);

		if ($Collection) {
			if (PerchUtil::count($ids)) {
				$sql = [];

				$prefix .= '.';

				foreach($ids as $item_id) {
					$sql[] = 'SELECT CONCAT('.$this->db->pdb($prefix).', indexKey) AS \'key\', indexValue AS \'value\' 
								FROM '.PERCH_DB_PREFIX.'collection_index ci, '.PERCH_DB_PREFIX.'collection_revisions cr
								WHERE (ci.collectionID=cr.collectionID AND ci.itemID=cr.itemID 
									AND ci.collectionID='.$this->db->pdb((int)$Collection->id()).' 
									AND ci.itemID='.$this->db->pdb((int)$item_id).' 
									AND ci.itemRev=cr.itemRev)
									AND ci.indexKey NOT LIKE \'%.%\'';
				}

				$sql = implode(' UNION ', $sql);

				return $this->db->get_rows($sql);
			}
		}

		return false;
	}


}