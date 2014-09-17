<?php

class PerchContent_PageTemplates extends PerchFactory
{
    protected $table = 'page_templates';
    protected $pk = 'templateID';
    protected $singular_classname = 'PerchContent_PageTemplate';
    protected $default_sort_column = 'templatePath';
    
    
    public function find_and_add_new_templates()
    {
        $files = $this->get_template_files();
        $templates = $this->all();
        $cache = array();
        
        if (PerchUtil::count($templates)) {
            foreach($templates as $Template) {
                $cache[] = $Template->templatePath();
            }
        }
        
        if (PerchUtil::count($files)) {
            foreach($files as $file) {
                if (!in_array($file['filename'], $cache)) {
                    // template is new
                    $data = array();
                    $data['templateTitle'] = $file['label'];
                    $data['templatePath'] = $file['filename'];
                    $data['optionsPageID'] = '0';
                    $this->create($data);
                }
            }
        }
    }
    
    public function get_templates($path=false, $include_hidden=false, $initial_path=false)
    {
        $Perch = Perch::fetch();
        
        if ($path===false) $path = PERCH_TEMPLATE_PATH.'/pages';
        if ($initial_path===false) $initial_path = $path;
        $a = array();
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if(substr($file, 0, 1) != '.' && $file!='attributes' && ($include_hidden || substr($file, 0, 1) != '_') && !preg_match($Perch->ignore_pattern, $file)) {
                        $extension = PerchUtil::file_extension($file);
                        if ($extension == 'php') {
                            $p = str_replace($initial_path, '', $path);
                            if (!$p) {
                                $a[PerchLang::get('General')][] = array('filename'=>$file, 'path'=>$file, 'label'=>$this->template_display_name($file));
                            }else{
                                $a[] = array('filename'=>$file, 'path'=>ltrim($p, '/').'/'.$file, 'label'=>$this->template_display_name($file));
                            }
                        }else{
                            $a[$this->template_display_name($file)] = $this->get_templates($path.'/'.$file, $include_hidden, $initial_path);
                        }
                    }
                }
                closedir($dh);
            }
            if (PerchUtil::count($a)) $a = PerchUtil::array_sort($a, 'label');
        }
        return $a;
    }

    
    private function get_template_files()
    {
        $a = array();
        $path = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/pages');
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    $full_path = PerchUtil::file_path($path.'/'.$file);
                    if(substr($file, 0, 1) != '.' && substr($file, 0, 1) != '_' && !is_dir($full_path)) {
                        $extension = PerchUtil::file_extension($file);
                        $a[] = array('filename'=>$file, 'path'=>$full_path, 'label'=>$this->template_display_name($file));
                    }
                }
                closedir($dh);
            }
        }
        return $a;
    }
    
    private function template_display_name($file_name)
    {
        $file_name = str_replace(array('.html', '.htm', '.php5', '.php'), '', $file_name);
        $file_name = str_replace('_', ' ', $file_name);
        $file_name = str_replace('-', ' - ', $file_name);
        
        $file_name = ucwords($file_name);
        
        return $file_name;
    }
}

?>