<?php

class PerchContent_SearchHandler implements PerchAPI_SearchHandler
{

    public static function get_admin_search_sql($key, $opts)
    {
        $db = PerchDB::fetch();
        $encoded_key = str_replace('"', '', PerchUtil::json_safe_encode($key));

        $sql = '   \''.__CLASS__.'\' AS source, \'Page Content\' AS display_source, MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') AS score, 
                r.regionPage AS col1, ci.itemSearch AS col2, ci.itemJSON AS col3, r.regionOptions AS col4, p.pageNavText AS col5, p.pageTitle AS col6, r.regionID AS col7, ci.itemID AS col8
                FROM '.PERCH_DB_PREFIX.'content_regions r, '.PERCH_DB_PREFIX.'content_items ci, '.PERCH_DB_PREFIX.'pages p
                WHERE r.regionID=ci.regionID AND r.regionRev=ci.itemRev AND r.pageID=p.pageID AND r.regionPage!=\'*\' 
                    AND (MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') OR MATCH(ci.itemSearch) AGAINST('.$db->pdb($encoded_key).') ) 
                    ';

        return $sql;

    }

    public static function get_search_sql($key)
    {
        $db = PerchDB::fetch();
        $encoded_key = str_replace('"', '', PerchUtil::json_safe_encode($key));
        $opts = func_get_arg(1);

        $sql = '   \''.__CLASS__.'\' AS source, MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') AS score, 
                r.regionPage AS col1, ci.itemSearch AS col2, ci.itemJSON AS col3, r.regionOptions AS col4, p.pageNavText AS col5, p.pageTitle AS col6, regionTemplate AS col7, r.regionKey AS col8
                FROM '.PERCH_DB_PREFIX.'content_regions r, '.PERCH_DB_PREFIX.'content_items ci, '.PERCH_DB_PREFIX.'pages p
                WHERE r.regionID=ci.regionID AND r.regionRev=ci.itemRev AND r.pageID=p.pageID AND r.regionPage!=\'*\' AND r.regionSearchable=1 
                    AND (MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') OR MATCH(ci.itemSearch) AGAINST('.$db->pdb($encoded_key).') )
                    AND (';

            if (is_array($opts['from-path']) && PerchUtil::count($opts['from-path'])) {
                $pathopts = [];
                foreach($opts['from-path'] as $frompath) {
                    $pathopts[] = 'r.regionPage LIKE '.$db->pdb($frompath.'%');
                }
                $sql .= implode(' OR ', $pathopts);
            } else {
                $sql .= 'r.regionPage LIKE '.$db->pdb($opts['from-path'].'%');
            }
            $sql .= ') ';

        return $sql;

    }
    
    public static function get_backup_search_sql($key)
    {
        $db = PerchDB::fetch();
        $opts = func_get_arg(1);

        // backup query using REGEXP
        $sql = ' \''.__CLASS__.'\' AS source, 0-(LENGTH(r.regionPage)-LENGTH(REPLACE(r.regionPage, \'/\', \'\'))) AS score, 
                r.regionPage AS col1, ci.itemSearch AS col2, ci.itemJSON AS col3, r.regionOptions AS col4, p.pageNavText AS col5, p.pageTitle AS col6, regionTemplate AS col7, r.regionKey AS col8
                FROM '.PERCH_DB_PREFIX.'content_regions r, '.PERCH_DB_PREFIX.'content_items ci, '.PERCH_DB_PREFIX.'pages p
                WHERE r.regionID=ci.regionID AND r.regionRev=ci.itemRev AND r.pageID=p.pageID AND r.regionPage!=\'*\' AND r.regionSearchable=1 
                    AND ci.itemSearch REGEXP '.$db->pdb('[[:<:]]'.$key.'[[:>:]]').' 
                    AND (';


            if (is_array($opts['from-path']) && PerchUtil::count($opts['from-path'])) {
                $pathopts = [];
                foreach($opts['from-path'] as $frompath) {
                    $pathopts[] = 'r.regionPage LIKE '.$db->pdb($frompath.'%');
                }
                $sql .= implode(' OR ', $pathopts);
            } else {
                $sql .= 'r.regionPage LIKE '.$db->pdb($opts['from-path'].'%');
            }
            $sql .= ') ';

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
        $_regionID = 'col7';
        $_itemID   = 'col8';

        $self = __CLASS__;

        $out = $self::format_result($key, $options, $result);
        $out['url'] = PERCH_LOGINPATH.'/core/apps/content/edit/?id='.$result[$_regionID].'&itm='.$result[$_itemID];

        return $out;
    }
    
}
