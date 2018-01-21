<?php

class PerchLang
{
    static protected $instance;
    public $lang_dir;
    public $lang_file;
    public $lang;
    
    private $translations = false;
    private $to_add = array();
    
    static protected $test;
    
    function __construct()
    {
        $Settings = PerchSettings::fetch();
        if (defined('PERCH_DB_DATABASE')) {
            $this->lang     = $Settings->get('lang')->settingValue();
        }
        
        if (!$this->lang) $this->lang     = 'en-gb';
        
        if (file_exists(PerchUtil::file_path(PERCH_PATH.'/addons/lang/'.$this->lang.'.txt'))) {
            $this->lang_dir = PerchUtil::file_path(PERCH_PATH.'/addons/lang');
        }else{
            $this->lang_dir = PerchUtil::file_path(PERCH_CORE.'/lang');
        }
        
        $this->lang_file = PerchUtil::file_path($this->lang_dir . '/' . $this->lang . '.txt');
    }
    
    function __destruct()
    {
        if (PerchUtil::count($this->to_add)) {
            $this->write_to_lang_file($this->to_add);
        }
    }
    
    public static function fetch()
	{	    
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
	}
    
    /**
     * Get translated string. First param is the string. Second can be an array of printf 
     * args, or will accept any number of params as printf args.
     *
     * @param string $string 
     * @param string $values 
     * @return string Translated string
     * @author Drew McLellan
     */
    public static function get($string, $values=false)
    {
        if (trim($string) == '') return '';

        $Lang = PerchLang::fetch();
        $string = $Lang->get_translated_string($string);
        
        if (func_num_args()>1) {
            if (is_array($values)) {
                $string = vsprintf($string, $values);
            }else{
                $args = func_get_args();
                array_shift($args);
                $string = vsprintf($string, $args);
            }
        }

        if (PERCH_TRANSLATION_ASSIST) { // koala mode 🐨 ô
            return 'Iñtërnât[['.$string.']]iônàližætiøn';
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

    
    public static function get_lang_options()
    {
        $Lang = PerchLang::fetch();
        
        $out = [];
        
        // Addons folder
        if (is_dir(PerchUtil::file_path(PERCH_PATH.'/addons/lang'))) {
            $lang_dir = PerchUtil::file_path(PERCH_PATH.'/addons/lang');
            
            $files = PerchUtil::get_dir_contents($lang_dir, false);
            if (is_array($files)) {
                foreach($files as $file) {
                    $out[] = PerchUtil::strip_file_extension($file);
                }
            }
        }
        
        
        // Core folder
        $lang_dir = PerchUtil::file_path(PERCH_CORE.'/lang');
        if (is_dir($lang_dir)) {
            $files = PerchUtil::get_dir_contents($lang_dir, false);
            if (is_array($files)) {
                foreach($files as $file) {
                    $f = PerchUtil::strip_file_extension($file);
                    if (!in_array($f, $out)) {
                        $out[] = $f;
                    }
                }
            }
        }
        
        if (PerchUtil::count($out)) {
            sort($out);
            return $out;
        }
        
        return [];
    }
    
    public function reload()
    {
        PerchUtil::debug('Reloading language data');
        $Settings = PerchSettings::fetch();
        $this->lang     = $Settings->get('lang')->settingValue();
        
        if (file_exists(PerchUtil::file_path(PERCH_PATH.'/addons/lang/'.$this->lang.'.txt'))) {
            $this->lang_dir = PerchUtil::file_path(PERCH_PATH.'/addons/lang');
        }else{
            $this->lang_dir = PerchUtil::file_path(PERCH_CORE.'/lang');
        }
        
        $this->lang_file = $this->lang_dir . DIRECTORY_SEPARATOR . $this->lang . '.txt';
        $this->translations= false;
        
    }
    
    
    private function load_translations()
    {
        $out = false;
        if (file_exists($this->lang_file)) {
            $json = file_get_contents($this->lang_file);
            $out  = PerchUtil::json_safe_decode($json, true);
        }

        if (is_array($out)) {
            $this->translations = $out;
        }else{
            $json = file_get_contents(PerchUtil::file_path(PERCH_CORE.'/lang/en-gb.txt'));
            $this->translations = PerchUtil::json_safe_decode($json, true);
            PerchUtil::debug('Unable to load language file: '. $this->lang, 'error');
        }
    }
    
    private function add_translation($string)
    {
        PerchUtil::debug('adding: '.$string);
        $string = preg_replace("/\s+/", ' ', $string);
        $this->to_add[$string] = $string;
    }
    
    private function write_to_lang_file($items)
    {
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