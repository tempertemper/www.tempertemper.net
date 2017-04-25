<?php

class PerchAssets_SearchHandler implements PerchAPI_SearchHandler
{

    public static function get_admin_search_sql($key, $opts)
    {
        $db = PerchDB::fetch();
        $encoded_key = str_replace('"', '', PerchUtil::json_safe_encode($key));

        $sql = 'SELECT  \''.__CLASS__.'\' AS source, \'Assets\' AS display_source, MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') AS score, 
                r.regionPage AS col1, ci.itemSearch AS col2, ci.itemJSON AS col3, r.regionOptions AS col4, p.pageNavText AS col5, p.pageTitle AS col6, r.regionID AS col7, ci.itemID AS col8
                FROM '.PERCH_DB_PREFIX.'content_regions r, '.PERCH_DB_PREFIX.'content_items ci, '.PERCH_DB_PREFIX.'pages p
                WHERE r.regionID=ci.regionID AND r.regionRev=ci.itemRev AND r.pageID=p.pageID AND r.regionPage!=\'*\' 
                    AND (MATCH(ci.itemSearch) AGAINST('.$db->pdb($key).') OR MATCH(ci.itemSearch) AGAINST('.$db->pdb($encoded_key).') ) 
                    ';


        $sql = 'SELECT  \''.__CLASS__.'\' AS source, \'Assets\' AS display_source, MATCH(r.resourceTitle) AGAINST('.$db->pdb($key).') AS score, 
                r.resourceTitle AS col1, 
                r.resourceFile AS col2, 
                r.resourceMimeType AS col3, 
                r.resourceFileSize AS col4, 
                r.resourceID AS col5, 
                \'\' AS col6, 
                r.resourceBucket AS col7, 
                r2.resourceFile AS col8 
                FROM '.PERCH_DB_PREFIX.'resources r, '.PERCH_DB_PREFIX.'resources r2
                WHERE r.resourceAWOL=0 AND 
                    r2.resourceKey=\'thumb\' AND r2.resourceParentID=r.resourceID AND
                    r.resourceKey=\'orig\'
                    AND (MATCH(r.resourceTitle) AGAINST('.$db->pdb($key).') OR MATCH(r.resourceTitle) AGAINST('.$db->pdb($encoded_key).') ) 
                    ';


        return $sql;

    }
    
    public static function format_admin_result($key, $options, $result)
    {
        $_regionID = 'col7';
        $_itemID   = 'col8';

        $self = __CLASS__;

        $out = $self::format_result($key, $options, $result);
        $out['url'] = PERCH_LOGINPATH.'/core/apps/assets/edit/?id='.$result['col5'];

        return $out;
    }

    public static function get_search_sql($key)
    {
    }

    public static function get_backup_search_sql($key)
    {
    }
    
    public static function format_result($key, $options, $result)
    {
        $_title     = $result['col1'];
        $_file      = $result['col2'];
        $_mime      = $result['col3'];
        $_filesize  = $result['col4'];
        $_id        = $result['col5'];
        $_bucket    = $result['col7'];
        $_thumb     = $result['col8'];

        $Bucket = PerchResourceBuckets::get($_bucket);

        $out = [];
        $out['title']  = $_title;
        $out['thumb'] = $Bucket->get_web_path_for_file($_thumb);

        if (isset($result['display_source'])) $out['display_source'] = $result['display_source'];
        
        return $out;
    }
    
}
