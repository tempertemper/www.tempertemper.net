<?php

class PerchContent_RunwaySearch implements PerchAPI_SearchHandler
{    
    public static function get_admin_search_sql($key)
    {
        $db = PerchDB::fetch(); 

        $sql = 'SELECT \''.__CLASS__.'\' AS source, \'Collections\' AS display_source, MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') AS score, 
                c.collectionKey AS col1, ci.itemSearch AS col2, ci.itemJSON AS col3, c.collectionOptions AS col4, c.collectionKey AS col5, c.collectionKey AS col6, c.collectionID AS col7, ci.itemID AS col8
                FROM '.PERCH_DB_PREFIX.'collections c, '.PERCH_DB_PREFIX.'collection_items ci, '.PERCH_DB_PREFIX.'collection_revisions cr
                WHERE c.collectionID=cr.collectionID AND cr.itemID=ci.itemID AND cr.itemRev=ci.itemRev 
                AND (MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') OR MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') )';
        
        return $sql;
    }

    public static function get_search_sql($key)
    {
        $db = PerchDB::fetch(); 

        $sql = 'SELECT \''.__CLASS__.'\' AS source, MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') AS score, 
                c.collectionKey AS col1, ci.itemSearch AS col2, ci.itemJSON AS col3, c.collectionOptions AS col4, c.collectionKey AS col5, c.collectionKey AS col6, collectionTemplate AS col7, c.collectionKey AS col8
                FROM '.PERCH_DB_PREFIX.'collections c, '.PERCH_DB_PREFIX.'collection_items ci, '.PERCH_DB_PREFIX.'collection_revisions cr
                WHERE c.collectionID=cr.collectionID AND cr.itemID=ci.itemID AND cr.itemRev=ci.itemRev AND c.collectionSearchable=1 AND cr.itemSearchable=1
                AND (MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') OR MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') )';
	    
	    return $sql;
    }
    
    public static function get_backup_search_sql($key)
    {
        $db = PerchDB::fetch(); 
        
        $sql = 'SELECT \''.__CLASS__.'\' AS source, 1 AS score, 
                c.collectionKey AS col1, ci.itemSearch AS col2, ci.itemJSON AS col3, c.collectionOptions AS col4, c.collectionKey AS col5, c.collectionKey AS col6, collectionTemplate AS col7, c.collectionKey AS col8
                FROM '.PERCH_DB_PREFIX.'collections c, '.PERCH_DB_PREFIX.'collection_items ci, '.PERCH_DB_PREFIX.'collection_revisions cr
                WHERE c.collectionID=cr.collectionID AND cr.itemID=ci.itemID AND cr.itemRev=ci.itemRev AND c.collectionSearchable=1 AND cr.itemSearchable=1
                    AND ci.itemSearch REGEXP '.$db->pdb('[[:<:]]'.$key.'[[:>:]]');
	    
	    return $sql;
    }
    
    public static function format_result($key, $options, $result)
    {
        $_contentPage    = 'col1';
        $_contentSearch  = 'col2';
        $_contentJSON    = 'col3';
        $_contentOptions = 'col4';
        $_pageNavText    = 'col5';
        $_pageTitle      = 'col6';
        $_regionTemplate = 'col7';
        $_regionKey      = 'col8';
                
        $lowerkey = strtolower($key);
        $item = PerchUtil::json_safe_decode($result[$_contentJSON], 1);

        if (PerchUtil::count($item)) {
        
            $loweritem     = strtolower($result[$_contentSearch]);
            $excerpt_chars = (int) $options['excerpt-chars'];
            $first_portion = floor(($excerpt_chars/4));
            
            $out = array();
            $out['url'] = $result[$_contentPage];
        
            $regionOptions = PerchUtil::json_safe_decode($result[$_contentOptions]);
            if ($regionOptions) {
                if (isset($regionOptions->searchURL) && $regionOptions->searchURL!='') {
                                     
                    $callback = function($matches) use ($item) {
                                    if (isset($item[$matches[1]])){
                                        return $item[$matches[1]];
                                    }
                                };
                    $out['url'] = preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', $callback, $regionOptions->searchURL);
                }
            }
        
            if (isset($item['_title'])) {
                $out['title'] = $item['_title'];
            }else{
                $out['title'] = $result[$_pageNavText];
            }

            $html = strip_tags(html_entity_decode($result[$_contentSearch]));

            $html = preg_replace('/\s{2,}/', ' ', $html);
            $pos = mb_stripos($html, $key);
            if ($pos<$first_portion){
                $lower_bound = 0;
            }else{
                $lower_bound = $pos-$first_portion;
            }
        
            $html = mb_substr($html, $lower_bound, $excerpt_chars);
        
            // trim broken works
            $parts = explode(' ', $html);
            array_pop($parts);
            array_shift($parts);
            $html = implode(' ', $parts);
        
            // keyword highlight
            $html = preg_replace('/('.preg_quote($key, '/').')/i', '<em class="keyword">$1</em>', $html);
            
            $out['excerpt']     = $html;
                   
            $out['pageTitle']   = $result[$_pageTitle];
            $out['pageNavText'] = $result[$_pageNavText];
            $out['source']      = $result['source'];
            $out['region_key']  = $result[$_regionKey];

            if (isset($result['display_source'])) $out['display_source'] = $result['display_source'];

            // duplicate vals
            foreach($out as $k=>$val) {
                $out['result_'.$k] = $val;
                if ($options['no-conflict']) {
                    unset($out[$k]);
                }
            }
              
            $out['search_key'] = $key;

            if (!$options['no-conflict']) {
                $out['key'] = $key; 
            }

            $out = array_merge($out, $item);
            
            return $out;         
        }
        return false;
    }

    public static function format_admin_result($key, $options, $result)
    {
        $_collectionID = 'col7';
        $_itemID   = 'col8';

        $self = __CLASS__;

        $out = $self::format_result($key, $options, $result);
        $out['url'] = PERCH_LOGINPATH.'/core/apps/content/collections/edit/?id='.$result[$_collectionID].'&itm='.$result[$_itemID];

        return $out;
    }
    
    private static function substitute_url_vars($matches)
	{
	    $url_vars = self::$tmp_url_vars;
    	if (isset($url_vars[$matches[1]])){
    		return $url_vars[$matches[1]];
    	}
	}
    
}
