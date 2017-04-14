<?php

class PerchAdminSearch 
{
	private $api;
	private $db;

	public function search($key, $opts, $Paging=false)
	{
		PerchUtil::debug('Search term: '.$key, 'success');

	    $this->api = new PerchAPI(1.0, 'content');
		$this->db  = PerchDB::fetch();

		$defaults = array();
		$defaults['template']           = 'search-result.html';
		$defaults['count']              = 10;
		$defaults['excerpt-chars']      = 1000;
		$defaults['from-path']          = '/';
		$defaults['hide-extensions']    = false;
		$defaults['add-trailing-slash'] = false;
		$defaults['hide-default-doc']   = false;
		$defaults['no-conflict']        = false;
		$defaults['skip-template']        = false;
		$defaults['apps']               = array();
		
		if (is_array($opts)) {
		    $opts = array_merge($defaults, $opts);
		}else{
		    $opts = $defaults;
		}

	    $search_method = 'get_admin_search_sql';
	    $format_method = 'format_admin_result';
		
		$search_handlers = PerchSystem::get_registered_search_handlers(true);

		$search_content = true;

		if (PerchUtil::count($opts['apps'])) {
		    $search_content = in_array('PerchContent', $opts['apps']);
		    
		    if (PerchUtil::count($search_handlers)) {
		        $new_handlers = array();
		        foreach($search_handlers as $handler) {
		            $short = str_replace('_SearchHandler', '', $handler);
		            if (in_array($short, $opts['apps'])) {
		                $new_handlers[] = $handler;
		            }
		        }
		        $search_handlers = $new_handlers;
		    }    
		}
		
		$out = array();

		if ($key!='') {

		    
		    $encoded_key = str_replace('"', '', PerchUtil::json_safe_encode($key));
		
		    if (!$Paging) $Paging = $this->api->get('Paging');
		
		    if (isset($opts['count'])) {
		        $Paging->set_per_page($opts['count']);
		        if (isset($opts['start']) && $opts['start']!='') {
		            $Paging->set_start_position($opts['start']);
		        }
		    }else{
		        $Paging->disable();
		    }
		
		    
		    // Proper query using FULLTEXT
		    $sql = $Paging->select_sql(); 
		
		    if (!$search_content) {            
		        $sql .= ' \'PerchContent_SearchHandler\' AS source, \'Page Content\' AS display_source, \'\' AS score, \'\' AS col1, \'\' AS col2, \'\' AS col3, \'\' AS col4, \'\' AS col5, \'\' AS col6, \'\' AS col7, \'\' AS col8 FROM '.$this->table.' WHERE 1=0  ';
		    }

		    if (PerchUtil::count($search_handlers)) {
		        $first = true;

		        foreach($search_handlers as $handler) {    
		            $handler_sql = false;
		            if (method_exists($handler, $search_method)) {
		                $handler_sql = call_user_func(array($handler, $search_method), $key, $opts);    
		            }
		            
		            if ($handler_sql) {
		                if ($first) {
		                    $sql .= ' '.$handler_sql.' ';
		                    $first = false;
		                }else{
		                    $sql .= ' 
		                    UNION 
		                    '.$handler_sql.' ';    
		                }
		                
		            }
		            $handler_sql = false;
		        }
		    }
		                
		    $sql .= ' ORDER BY score DESC';
		        
		    if ($Paging->enabled()) {
		        $sql .= ' '.$Paging->limit_sql();
		    }        
		        
		    $rows = $this->db->get_rows($sql);

		    $total_count = $this->db->get_value($Paging->total_count_sql());
            $Paging->set_total($total_count);
		
		    if (PerchUtil::count($rows)==0) {
		    
		        if ($search_content) { 
		            $sql = $Paging->select_sql();
		        }else{
		            $sql = $Paging->select_sql() . ' \'PerchContent_SearchHandler\' AS source, \'Page Content\' AS display_source, \'\' AS score, \'\' AS col1, \'\' AS col2, \'\' AS col3, \'\' AS col4, \'\' AS col5, \'\' AS col6, \'\' AS col7, \'\' AS col8 FROM '.$this->table.' WHERE 1=0 ';
		        }
		                    
		        if (PerchUtil::count($search_handlers)) {
		            $first = true;
		            foreach($search_handlers as $handler) {
		                $handler_sql = call_user_func(array($handler, 'get_backup_search_sql'), $key, $opts);
		                if ($handler_sql) {
		                    if ($first) {
		                        $sql .= ' '.$handler_sql.' ';
		                        $first = false;
		                    }else{
		                        $sql .= ' 
		                        UNION 
		                        '.$handler_sql.' ';    
		                    }
		                }
		                $handler_sql = false;
		            }
		        }
		        
		        $sql .= ' ORDER BY score ASC ';

		        if ($Paging->enabled()) {
		            $sql .= ' '.$Paging->limit_sql();
		        }        

		        $rows = $this->db->get_rows($sql);

		        $total_count = $this->db->get_value($Paging->total_count_sql());
                $Paging->set_total($total_count);
		    }
		
		        
		    if (PerchUtil::count($rows)) {

		    	$paging_array = $Paging->to_array($opts);
		    	PerchUtil::debug($paging_array);

		        foreach($rows as $row) {
		            $className = $row['source'];
		            if (method_exists($className, $format_method)) {
		                $r = call_user_func(array($className, $format_method), $key, $opts, $row);    
		            }else{
		                $r = false;
		            }
		            
		            if ($r) {

		            	$r = array_merge($r, $paging_array);

		                $r['source'] = str_replace('_SearchHandler', '', $row['source']);

		                // duplicate vals
		                foreach($r as $k=>$val) {
		                    $r['result_'.$k] = $val;
		                    if ($opts['no-conflict']) {
		                        unset($r[$k]);
		                    }
		                }
		                  
		                $r['search_key'] = $key;
		                if (!$opts['no-conflict']) {
		                    $r['key'] = $key; 
		                }

		                

		                $out[] = $r; 
		            } 
		        }
		    }
		}
		
		if (isset($opts['skip-template']) && $opts['skip-template']) {
		    return $out;
		}
		

		$Template = new PerchTemplate(PERCH_CORE.'/templates/search/'.$opts['template'], 'search', false);
		$Template->enable_encoding();
		
		if (PerchUtil::count($out)) {
		    return $Template->render_group($out, 1);
		}else{
		    $Template->use_noresults();
		    return $Template->render(array('search_key'=>$key, 'key'=>$key));
		}
	}
}