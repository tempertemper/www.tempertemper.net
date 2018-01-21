<?php

class PerchContent_Runway extends PerchContent
{
	private $custom_collection_cache = array();

	public function get_collection($key=false, $opts=false) 
	{
	    if ($key === false) return ' ';

	    $db     = PerchDB::fetch();
	    $Perch  = Perch::fetch();

	    $where = '';

	    $collection_key_for_cache = $key;
	    if (is_array($key)) $collection_key_for_cache = implode('-', $key);

		$cache_key = ':'.$collection_key_for_cache;
	          
	            
	    if (array_key_exists($cache_key, $this->custom_collection_cache)) {
	        $collections = $this->custom_collection_cache[$cache_key];
	    }else{
	        $sql    = 'SELECT collectionID, collectionTemplate  FROM '.PERCH_DB_PREFIX. 'collections WHERE ';
	        
	        if (is_array($key)) {
	            $sql .= '(';
	                $key_items = array();
	                foreach($key as $k) {
	                    $key_items[] = 'collectionKey='.$db->pdb($k);
	                }
	                $sql .= implode(' OR ', $key_items);
	            $sql .= ')';
	        }else{
	            $sql .= 'collectionKey='.$db->pdb($key);
	        }            

	        $collections    = $db->get_rows($sql);

	        $this->custom_collection_cache[$cache_key] = $collections;
	    }

	    if (!PerchUtil::count($collections)) {
	        $str_key = $key;
	        if (is_array($key)) {
	            $str_key = implode(', ', $key);
	        }
	        PerchUtil::debug('No matching collections found. Check collection name ('.$str_key.').', 'error');
	    }

	    $filter_mode = false;

	    $content = array();

	    $rev = false;
	    $rev_field = 'itemRev';

	    if ($this->preview){ 
	    	$rev_field = 'itemLatestRev';
	        if ($this->preview_rev!==false && $this->preview_contentID!='all') {	
	        	$rev = $this->preview_contentID;
	        }
	    }


	    // find specific _id
	    if (isset($opts['_id'])) {
	        $item_id = (int)$opts['_id'];

	        $Paging = false;

	        $sql = 'SELECT cr.itemID, cr.collectionID, ci.itemJSON 
	        		FROM '.PERCH_DB_PREFIX.'collection_revisions cr, '.PERCH_DB_PREFIX.'collection_items ci, '.PERCH_DB_PREFIX.'collections c 
	        		WHERE cr.collectionID=c.collectionID AND  cr.itemID=ci.itemID AND cr.itemID='.$this->db->pdb((int)$item_id).' ';

	        if ($rev) {
	        	$sql .= ' AND ci.itemRev='.$this->db->pdb((int)$rev);
	        }else{
	        	
	        	$sql .= ' AND cr.'.$rev_field.'=ci.itemRev';
	        }

	        if (PerchUtil::count($collections)) {
	            $where = array();
	            foreach($collections as $collection) {
	                $where[] = 'c.collectionID='.$this->db->pdb((int)$collection['collectionID']).'';
	            }
	            $sql .= ' AND ('.implode(' OR ', $where).')';
	        }else{
	            $sql .= ' AND c.collectionID IS NULL ';
	        }

	        $sql .= ' LIMIT 1 ';

	        $rows = $db->get_rows($sql);

	    }else{
	        $sortval = ' idx2.indexValue as sortval ';

	        if (isset($opts['paginate']) && $opts['paginate']) {
	            if (isset($opts['pagination-var'])) {
	                $Paging = new PerchPaging($opts['pagination-var']);
	            }else{
	                $Paging = new PerchPaging();
	            }
	            $sql = $Paging->select_sql();
	        }else{
	            $sql = 'SELECT';
	        }

	        if ($rev) {
	        	$rev_query = '';
	        }else{
	        	$rev_query = 'JOIN '.PERCH_DB_PREFIX.'collection_revisions cr ON idx.itemID=cr.itemID AND idx.itemRev=cr.'.$rev_field.' AND idx.collectionID=ci.collectionID';
	        }
	        
	        $sql .= ' * FROM ( SELECT  idx.itemID, ci.collectionID, ci.itemJSON, '.$sortval.' FROM '.PERCH_DB_PREFIX.'collection_index idx 
	                        JOIN '.PERCH_DB_PREFIX.'collection_items ci ON idx.itemID=ci.itemID AND idx.itemRev=ci.itemRev AND idx.collectionID=ci.collectionID
	                        '.$rev_query.'
	                        JOIN '.PERCH_DB_PREFIX.'collection_index idx2 ON idx.itemID=idx2.itemID AND idx.itemRev=idx2.itemRev  ';

	        if (isset($opts['sort'])) {
	            $sql .= ' AND idx2.indexKey='.$db->pdb($opts['sort']).' ';
	        }else{
	            $sql .= ' AND idx2.indexKey='.$db->pdb('_order').' ';
	        }

	        if (PerchUtil::count($collections)) {
	            $where = array();
	            foreach($collections as $collection) {
	                $where[] = 'idx.collectionID='.$this->db->pdb((int)$collection['collectionID']).'';
	            }
	            $where_clause = ' WHERE ('.implode(' OR ', $where).')';
	        }else{
	            $where_clause = ' WHERE idx.collectionID IS NULL ';
	        }

	        if ($rev) {
	        	$where_clause .= ' AND idx.itemRev='.$rev.' ';
	        }

	        $sql .= $where_clause;


	        // Categories
	        if (isset($opts['category'])) {
	            $cats = $opts['category'];
	            if (!is_array($cats)) $cats = array($cats);

	            $match = 'any';
	            if (isset($opts['category-match'])) {
	                $match = (strtolower($opts['category-match'])=='any' ? 'any' : 'all');
	            }
	            
	            $pos = array();
	            $neg = array();

	            if (count($cats)) {
	                foreach($cats as $cat) {
	                    if (substr($cat, 0, 1)=='!') {
	                        $neg[] = substr($cat, 1);
	                    }else{
	                        $pos[] = $cat;
	                    }
	                }

	                $sql .= $this->_get_category_sql($pos, false, $match, $where_clause);
	                $sql .= $this->_get_category_sql($neg, true, $match, $where_clause);
	            }

	        }


	        // if not picking an _id, check for a filter
	        if (isset($opts['filter']) && (isset($opts['value']) || is_array($opts['filter']))) {
	            
	            // if it's not a multi-filter, make it look like one to unify what we're working with
	            if (!is_array($opts['filter']) && isset($opts['value'])) {
	                $filters = array(
	                                array(
	                                    'filter'     => $opts['filter'],
	                                    'value'      => $opts['value'],
	                                    'match'      => (isset($opts['match']) ? $opts['match'] : 'eq'),
	                                    'match-type' => (isset($opts['match-type']) ? $opts['match-type'] : 'alpha')
	                                )
	                            );
	                $filter_mode = 'AND';
	            }else{
	                $filters = $opts['filter'];
	                $filter_mode = 'AND';

	                if (isset($opts['match']) && strtolower($opts['match'])=='or') {
	                    $filter_mode = 'OR';
	                }
	            }


	            $where = array();

	            foreach($filters as $filter) {                       
	                $key = $filter['filter'];
	                $val = $filter['value'];
	                $match = isset($filter['match']) ? $filter['match'] : 'eq';

	                if (is_numeric($val)) $val = (float) $val;

	                switch ($match) {
	                    case 'eq': 
	                    case 'is': 
	                    case 'exact': 
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue='.$db->pdb($val).')';
	                        break;
	                    case 'neq': 
	                    case 'ne': 
	                    case 'not': 
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue != '.$db->pdb($val).')';
	                        break;
	                    case 'gt':
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue > '.$db->pdb($val).')';
	                        break;
	                    case 'gte':
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue >= '.$db->pdb($val).')';
	                        break;
	                    case 'lt':
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue < '.$db->pdb($val).')';
	                        break;
	                    case 'lte':
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue <= '.$db->pdb($val).')';
	                        break;
	                    case 'contains':
	                        $v = str_replace('/', '\/', $val);
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue REGEXP '.$db->pdb('[[:<:]]'.$v.'[[:>:]]').')';
	                        break;
	                    case 'notcontains':
	                        $v = str_replace('/', '\/', $val);
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue NOT REGEXP '.$db->pdb('[[:<:]]'.$v.'[[:>:]]').')';
	                        break;
	                    case 'regex':
	                    case 'regexp':
	                        $v = str_replace('/', '\/', $val);
	                        $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue REGEXP '.$db->pdb($v).')';
	                        break;
	                    case 'between':
	                    case 'betwixt':
	                        $vals  = explode(',', $val);
	                        if (PerchUtil::count($vals)==2) {

	                            $vals[0] = trim($vals[0]);
	                            $vals[1] = trim($vals[1]);

	                            if (is_numeric($vals[0]) && is_numeric($vals[1])) {
	                                $vals[0] = (float)$vals[0];
	                                $vals[1] = (float)$vals[1];
	                            }

	                            $where[] = '(idx.indexKey='.$db->pdb($key).' AND (idx.indexValue > '.$db->pdb($vals[0]).' AND idx.indexValue < '.$db->pdb($vals[1]).'))';
	                        }
	                        break;
	                    case 'eqbetween':
	                    case 'eqbetwixt':
	                        $vals  = explode(',', $val);
	                        if (PerchUtil::count($vals)==2) {
	                            $vals[0] = trim($vals[0]);
	                            $vals[1] = trim($vals[1]);

	                            if (is_numeric($vals[0]) && is_numeric($vals[1])) {
	                                $vals[0] = (float)$vals[0];
	                                $vals[1] = (float)$vals[1];
	                            }

	                            $where[] = '(idx.indexKey='.$db->pdb($key).' AND (idx.indexValue >= '.$db->pdb($vals[0]).' AND idx.indexValue <= '.$db->pdb($vals[1]).'))';
	                            
	                        }
	                        break;
	                    case 'in':
	                    case 'within':
	                        $vals  = explode(',', $val);
	                        if (PerchUtil::count($vals)) {
	                            $where[] = '(idx.indexKey='.$db->pdb($key).' AND idx.indexValue IN ('.$db->implode_for_sql_in($vals).'))';                          
	                        }
	                        break;
	                }
	            }
	            $sql .= ' AND ('.implode($where, ' OR ').') ';
	        }

	        $sql .= ' AND idx.itemID=idx2.itemID AND idx.itemRev=idx2.itemRev
	                ) as tbl GROUP BY itemID, itemJSON, sortval '; // DM added ', itemJSON, sortval' for MySQL 5.7

	        if ($filter_mode=='AND' && PerchUtil::count($filters)>1) {
	            $sql .= ' HAVING count(*)='.PerchUtil::count($filters).' ';
	        }

	        // sort
	        if (isset($opts['sort'])) {

	            $direction = 'ASC';
	            if (isset($opts['sort-order'])) {
	                switch($opts['sort-order']) {
	                    case 'DESC':
	                    case 'desc':
	                        $direction = 'DESC';
	                        break;

	                    case 'RAND':
	                    case 'rand':
	                        $direction = 'RAND';
	                        break;

	                    default:
	                        $direction = 'ASC';
	                        break;
	                }
	            }

	            if ($direction=='RAND') {
	                $sql .= ' ORDER BY RAND()';
	            }else{
	                if (isset($opts['sort-type']) && $opts['sort-type']=='numeric') {
	                    $sql .= ' ORDER BY sortval * 1 '.$direction .' ';
	                }else{
	                    $sql .= ' ORDER BY sortval '.$direction .' ';
	                }
	            }
	           
	        }else{
	            if (isset($opts['sort-type']) && $opts['sort-type']=='numeric') {
	                $sql .= ' ORDER BY sortval * 1 ASC ';
	            }else{
	                $sql .= ' ORDER BY sortval ASC ';
	            }
	        }

	        // Pagination
	        if (isset($opts['paginate']) && $opts['paginate']) {
	            if (isset($opts['pagination-var'])) {
	                $Paging = new PerchPaging($opts['pagination-var']);
	            }else{
	                $Paging = new PerchPaging();
	            }
	            
	            $Paging->set_per_page(isset($opts['count'])?(int)$opts['count']:10);
	            
	            $opts['count'] = $Paging->per_page();
	            $opts['start'] = $Paging->lower_bound()+1;
	            
	        }else{
	            $Paging = false;
	        }
	                
	        // limit
	        if (isset($opts['count']) || isset($opts['start'])) {

	            // count
	            if (isset($opts['count'])) {
	                $count = (int) $opts['count'];
	            }else{
	                $count = false;
	            }
	            
	            // start
	            if (isset($opts['start'])) {
	                $start = ((int) $opts['start'])-1; 
	            }else{
	                $start = 0;
	            }

	            if (is_object($Paging)) {
	                $sql .= $Paging->limit_sql();
	            }else{
	                $sql .= ' LIMIT '.$start; 
	                if ($count) $sql .= ', '.$count;
	            }
	        }
	
	        $rows = $db->get_rows($sql);

	        if (is_object($Paging)) {
	            $total_count = $this->db->get_value($Paging->total_count_sql());
	            $Paging->set_total($total_count);
	        }
	    }

	    // transform json
	    if (PerchUtil::count($rows)) {
	        $content = array();
	        foreach($rows as $item) {
	            if (trim($item['itemJSON'])!='') {
	                $tmp = PerchUtil::json_safe_decode($item['itemJSON'], true);

	                if (isset($item['sortval'])) $tmp['_sortvalue'] = $item['sortval'];

	                // 'each' callback
	                if (isset($opts['each'])) {
	                    if (is_callable($opts['each'])) {
	                        $tmp = $opts['each']($tmp);
	                    }
	                }
	                $content[] = $tmp;
	            }
	        }
	    }

	    if (isset($opts['skip-template']) && $opts['skip-template']==true) {
	        if (isset($opts['raw']) && $opts['raw']==true) {
	            if (PerchUtil::count($content)) {
	                foreach($content as &$item) {
	                    if (PerchUtil::count($item)) {
	                        foreach($item as &$field) {
	                            if (is_array($field) && isset($field['raw'])) {
	                                $field = $field['raw'];
	                            }
	                        }
	                    }
	                }
	            }
	            return $content; 
	        }
	    }
	    
	    // template
	    if (isset($opts['template'])) {
	        $template = $opts['template'];
	    }else{
	        $template = $collections[0]['collectionTemplate'];
	    }
	    
	    $Template = new PerchTemplate('content/'.$template, 'content');
	    
	    if (!$Template->file) {
	        return 'The template <code>' . PerchUtil::html($template) . '</code> could not be found.';
	    }
	    
	    // post process
	    $tags   = $Template->find_all_tags('content');
	    $processed_vars = array();
	    $used_items = array();
	    foreach($content as $item) {
	        $tmp = $item;
	        if (PerchUtil::count($tags)) {
	            foreach($tags as $Tag) {
	                if (isset($item[$Tag->id])) {                         
	                    $used_items[] = $item;
	                }
	            }
	        }
	        if ($tmp) $processed_vars[] = $tmp;
	    }
	    
	    // Paging to template
	    if (is_object($Paging) && $Paging->enabled()) {
	        $paging_array = $Paging->to_array($opts);
	        // merge in paging vars
	        foreach($processed_vars as &$item) {
	            foreach($paging_array as $key=>$val) {
	                $item[$key] = $val;
	            }
	        }
	    }
	    
	    if (PerchUtil::count($processed_vars)) {

	    	if (isset($opts['split-items']) && $opts['split-items']) {
	    	    $html = $Template->render_group($processed_vars, false);
	    	}else{
	    	    $html = $Template->render_group($processed_vars, true);    
	    	}
	    	
	    }else{
	        $Template->use_noresults();
	        $html = $Template->render(array());
	    }
	    
	    if (isset($opts['skip-template']) && $opts['skip-template']==true) {
	        $out = array();

	        if (PerchUtil::count($processed_vars)) {

	        	if (isset($opts['api']) && $opts['api']==true) {
                    $field_type_map = $Template->get_field_type_map();
                    $api = true;
                } else {
                    $api = false;
                }

	        	$category_field_ids    = $Template->find_all_tag_ids('categories');

	            foreach($processed_vars as &$item) {
	                if (PerchUtil::count($item)) {
	                    foreach($item as $key => &$field) {

	                    	if (in_array($key, $category_field_ids)) {
	                    	    $field = $this->_process_category_field($field);
	                    	}

	                    	if ($api) {
                                if (array_key_exists($key, $field_type_map)) {
                                    $field = $field_type_map[$key]->get_api_value($field);
                                    continue;
                                }
                            }

	                        if (is_array($field) && isset($field['processed'])) {
	                            $field = $field['processed'];
	                        }
	                        if (is_array($field) && isset($field['_default'])) {
	                            $field = $field['_default'];
	                        }
	                    }
	                }
	            }
	        }

	        for($i=0; $i<PerchUtil::count($content); $i++) {
	            $out[] = array_merge($content[$i], $processed_vars[$i]);
	        }

	        if (isset($opts['return-html'])&& $opts['return-html']==true) $out['html'] = $html;

	        return $out;
	    }
	    
	    return $html;
	}

	private function _get_category_sql($items, $negative_match=false, $match, $where_clause)
	{
	    if (count($items)) {
	        $cat_sql = 'SELECT idx.itemID FROM '.PERCH_DB_PREFIX.'collection_index idx 
	        				JOIN '.PERCH_DB_PREFIX.'collection_revisions c ON idx.itemID=c.itemID AND idx.itemRev=c.itemRev AND idx.collectionID=c.collectionID '.$where_clause. ' AND ';

	        $where = array();
	        foreach($items as $item) {
	            $where[] = '(idx.indexKey=\'_category\' AND idx.indexValue LIKE '.$this->db->pdb($item.'%').')';
	        }
	        $cat_sql .= '('.implode(' OR ', $where).')';

	        if ($match=='all') {
	            $cat_sql .= ' GROUP BY idx.itemID HAVING COUNT(idx.itemID)='.count($items).' ';
	        }
	        $cat_results = $this->db->get_rows_flat($cat_sql);
	        
	        if (!PerchUtil::count($cat_results)) {
	            $cat_results = array(null);
	            if ($negative_match) return ''; // Return nothing if there are no categories to match against
	        }    

	        if ($negative_match) {
	            return ' AND idx.itemID NOT IN ('.$this->db->implode_for_sql_in($cat_results, true).') ';            
	        }

	        return ' AND idx.itemID IN ('.$this->db->implode_for_sql_in($cat_results, true).') ';            
	                       
	    }

	    return '';
	}

}