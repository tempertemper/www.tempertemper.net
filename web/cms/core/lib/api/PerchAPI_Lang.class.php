<?php

class PerchAPI_Lang
{
    public $lang_dir;
    public $lang_file;
    public $lang;
    
    private $translations = false;
    private $to_add = array();
    
    
    function __construct($version, $app_id=false)
    {
        $Settings = PerchSettings::fetch();
        if (defined('PERCH_DB_DATABASE')) {
            $this->lang = $Settings->get('lang')->settingValue();
        }
        
        if (!$this->lang) {
            $this->lang = 'en-gb';
        } 
        
        $ds = DIRECTORY_SEPARATOR;
        
        if (!$app_id) {
            $current_app = PerchUtil::get_current_app();
            $path        = $current_app['section'];
        }else{
            $path        = 'addons'.$ds.'apps'.$ds.$app_id;
        }        
        
        $this->lang_dir  = PERCH_PATH . $ds . $path . $ds . 'lang';
        $this->lang_file = $this->lang_dir . $ds . $this->lang . '.txt';
    }
    
    function __destruct()
    {
        if (PerchUtil::count($this->to_add)) {
            $this->write_to_lang_file($this->to_add);
        }
    }
    
    
    public function get($string, $values=false)
    {

        $string = $this->get_translated_string($string);
        
        if (func_num_args()>1) {
            if (is_array($values)) {
                $string = vsprintf($string, $values);
            }else{
                $args = func_get_args();
                array_shift($args);
                $string = vsprintf($string, $args);
            }
        }
        
        return $string;
    }


    public function get_translated_string($string)
    {
        if (!$this->translations) {
            $this->load_translations();
        }
        
        if (isset($this->translations[$string])) {
            return $this->translations[$string];
        }else{
            $this->add_translation($string);
        }
        return $string;
    }  
    
    private function load_translations()
    {
        $out = false;
        
        if (file_exists($this->lang_file)) {
            $json = file_get_contents($this->lang_file);
            $out  = PerchUtil::json_safe_decode($json, true);
        }else{
            if (is_writable($this->lang_dir)) touch($this->lang_file);
            $out  = array();
        }

        if (is_array($out)) {
            $this->translations = $out;
        }else{
            $json = file_get_contents($this->lang_dir . DIRECTORY_SEPARATOR . 'en-gb.txt');
            $this->translations = PerchUtil::json_safe_decode($json, true);
            PerchUtil::debug('Unable to load language file: '. $this->lang, 'error');
        }
    }
    
    private function add_translation($string)
    {
        PerchUtil::debug('Adding: '.$string);
        $string = preg_replace("/\s+/", ' ', $string);
        $this->to_add[$string] = $string;
    }
    
    private function write_to_lang_file($items)
    {
        $this->load_translations();
        
        if (!is_array($this->translations)) {
            $this->translations = array('lang'=>$this->lang);
        }
        
        $out = array_merge($this->translations, $items);
        
        $tidy_json = true;

        $json = PerchUtil::json_safe_encode($out, $tidy_json);
        
        if (is_writable($this->lang_file)) {
            file_put_contents($this->lang_file, $json);
        }
    }
    
}
