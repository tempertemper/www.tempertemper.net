<?php

class PerchSystem
{
    private static $search_handlers       = [];
    private static $admin_search_handlers = [];
    private static $bucket_handlers       = [];
    private static $template_filters      = [];
    private static $template_vars         = [];
    private static $attribute_vars        = [];
    private static $feathers              = [];
    private static $template_handlers     = [];
    private static $shortcode_providers   = [];
    private static $RoutedPage            = false;
    private static $Page                  = false;
    
    public static function set_page($page)
    {
        $Perch = Perch::fetch();

        if (PERCH_RUNWAY && $page instanceof PerchRoutedPage) {
            $Perch->set_page($page->path);
            self::$RoutedPage = $page;
        }else{
            $Perch->set_page($page);    
        }
        
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

    public static function set_page_object(PerchContent_Page $Page)
    {
        self::$Page = $Page;
    }

    public static function get_page_object()
    {
        return self::$Page;
    }

    public static function use_error_page($http_status=404)
    {
        if (PERCH_RUNWAY && self::$RoutedPage) {
            $RP         = self::$RoutedPage;
            $Router     = new PerchRouter;
            $RoutedPage = new PerchRoutedPage($RP->request_uri, $RP->path, $RP->query, $RP->args, false, $http_status);
            perch_runway_dispatch_page($RoutedPage, true);
        }
    }

    public static function is_api_request()
    {
        if (PERCH_RUNWAY && self::$RoutedPage) {
            return self::$RoutedPage->api_request;
        }
        return false;
    }

    public static function register_template_filter($filterName, $className)
    {
        if (!array_key_exists($filterName, self::$template_filters)) self::$template_filters[$filterName] = $className;
        return true;
    }

    public static function get_registered_template_filters()
    {
        return self::$template_filters;
    }
    
    public static function register_search_handler($className)
    {
        if (!in_array($className, self::$search_handlers)) self::$search_handlers[] = $className;
        return true;
    }
    
    public static function register_admin_search_handler($className)
    {
        if (!in_array($className, self::$admin_search_handlers)) self::$admin_search_handlers[] = $className;
        return true;
    }

    public static function get_registered_search_handlers($admin=false)
    {
        if ($admin) {
            return self::$admin_search_handlers;
        }
        return self::$search_handlers;
    }

    public static function register_shortcode_provider($className)
    {
        if (!in_array($className, self::$shortcode_providers)) self::$shortcode_providers[] = $className;
        return true;
    }

    public static function get_registered_shortcode_providers()
    {
        return self::$shortcode_providers;
    }

    public static function register_bucket_handler($ref, $className)
    {
        self::$bucket_handlers[$ref] = $className;
        return true;
    }
    
    public static function get_registered_bucket_handlers()
    {
        return self::$bucket_handlers;
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


    public static function get_url_var($var)
    {
        if (self::$RoutedPage && isset(self::$RoutedPage->args[$var])) {
            return self::$RoutedPage->args[$var];
        }
        return false;
    }

    public static function set_attr_var($var, $value=false)
    {
        self::$attribute_vars[$var] = $value;
    }

    public static function set_attr_vars($vars)
    {
        if (PerchUtil::count($vars)) {
            self::$attribute_vars = array_merge(self::$attribute_vars, $vars);
        }
    }

    public static function get_attr_var($var)
    {
        if (isset(self::$attribute_vars[$var])) {
            return self::$attribute_vars[$var];
        }
        
        return false;
    }
    
    public static function get_attr_vars()
    {
        return self::$attribute_vars;
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
               PerchUtil::redirect(PerchUtil::url_to_ssl($_SERVER['REQUEST_URI']), 301);
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
                PerchUtil::redirect(PerchUtil::url_to_non_ssl($_SERVER['REQUEST_URI']), 301);
            }
        }
    }
}