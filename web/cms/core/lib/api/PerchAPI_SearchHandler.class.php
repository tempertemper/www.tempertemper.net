<?php

interface PerchAPI_SearchHandler
{
    public static function get_search_sql($key);
    public static function get_backup_search_sql($key);
    public static function format_result($key, $opts, $result);
}
