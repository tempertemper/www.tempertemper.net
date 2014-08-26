<?php

class PerchSystem
{
    private static $search_handlers           = array();
    private static $template_vars             = array();
    private static $feathers                  = array();
    private static $template_handlers         = array();
    
    public static function set_page($page)
    {
        $Perch = Perch::fetch();
        $Perch->set_page($page);
        $Content = PerchContent::fetch();
        $Content->clear_cache();
    }

    public static function get_page($as_set=false)
    {
        $Perch = Perch::fetch();
        if ($as_set) {
            return $Perch->get_page_as_set();
        }
        return $Perch->get_page();
    }
    
    public static function register_search_handler($className)
    {
        self::$search_handlers[] = $className;
        return true;
    }
    
    public static function get_registered_search_handlers()
    {
        return self::$search_handlers;
    }

    public static function register_feather($className)
    {
        self::$feathers[] = $className;
        return true;
    }
    
    public static function get_registered_feathers()
    {
        return self::$feathers;
    }

    public static function register_template_handler($className)
    {
        self::$template_handlers[] = $className;
        return true;
    }

    public static function get_registered_template_handlers()
    {
        return self::$template_handlers;
    }
    
    public static function set_var($var, $value=false)
    {
        self::$template_vars[$var] = $value;
    }
    
    public static function unset_var($var)
    {
        if (isset(self::$template_vars[$var])) unset(self::$template_vars[$var]);
    }
    
    public static function set_vars($vars)
    {
        if (PerchUtil::count($vars)) {
            self::$template_vars = array_merge(self::$template_vars, $vars);
        }
    }
    
    public static function get_var($var)
    {
        if (isset(self::$template_vars[$var])) {
            return self::$template_vars[$var];
        }
        
        return false;
    }
    
    public static function get_vars()
    {
        self::$template_vars['perch_page_path'] = self::get_page();
        return self::$template_vars;
    }

    public static function get_helper_js()
    {
        $Settings = PerchSettings::fetch();
        if ($Settings->get('content_frontend_edit')->val()) {
            $Content = PerchContent::fetch();
            $Page = $Content->get_page();
            $r = '';
            if (is_object($Page)) {
                $r = '<script src="'.PERCH_LOGINPATH.'/core/assets/js/public_helper.js" async></script>';
                $r .= '<script async>var cms_path=\''.PerchUtil::html(PERCH_LOGINPATH.'/core/apps/content/page/?id='.$Page->id()).'\';</script>';    
            }
            return $r;
        }
        return false;
    }

    public static function redirect($url)
    {
        PerchUtil::redirect($url);
    }

    public static function force_ssl()
    {
        Perch::fetch(); // to define PERCH_SSL
        if (PERCH_SSL) {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
               PerchUtil::redirect(PerchUtil::url_to_ssl($_SERVER['REQUEST_URI']));
            } else {
                header('Strict-Transport-Security: max-age=31536000');
            }
        }
    }

    public static function force_non_ssl()
    {
        Perch::fetch(); // to define PERCH_SSL
        if (PERCH_SSL) {
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                header('Strict-Transport-Security: max-age=0');
                PerchUtil::redirect(PerchUtil::url_to_non_ssl($_SERVER['REQUEST_URI']));
            }
        }
    }
}