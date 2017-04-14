<?php

class PerchContent_PageTemplates extends PerchFactory
{
    protected $table = 'page_templates';
    protected $pk = 'templateID';
    protected $singular_classname = 'PerchContent_PageTemplate';
    protected $default_sort_column = 'templatePath';
    
    
    public function all($Paging=false)
    {
        $sort_val = null;
        $sort_dir = null;

        if ($Paging && $Paging->enabled()) {
            $sql = $Paging->select_sql();
            list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();
        }else{
            $sql = 'SELECT';
        }
        
        $sql .= ' *, (LENGTH(templatePath) - LENGTH(REPLACE (templatePath, "/", "")))  AS depth 
                FROM ' . $this->table;


        if ($sort_val) {
            $sql .= ' ORDER BY '.$sort_val.' '.$sort_dir;
        } else {
            if (isset($this->default_sort_column)) {
                $sql .= ' ORDER BY depth, ' . $this->default_sort_column . ' '.$this->default_sort_direction;
            }
        }

                
        
        
        if ($Paging && $Paging->enabled()) {
            $sql .=  ' '.$Paging->limit_sql();
        }
        
        $results = $this->db->get_rows($sql);
        
        if ($Paging && $Paging->enabled()) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }
        
        return $this->return_instances($results);
    }


    public function get_filtered($id_list=false)
    {
        if ($id_list == false || $id_list=='*' || $id_list=='') {
            return $this->all();    
        }

        $ids = explode(',', $id_list);

        $sql = 'SELECT *, (LENGTH(templatePath) - LENGTH(REPLACE (templatePath, "/", "")))  AS depth 
                FROM ' . $this->table .'
                WHERE templateID IN ('.$this->db->implode_for_sql_in($ids).')';
                
        $sql .= ' ORDER BY depth, ' . $this->default_sort_column . ' '.$this->default_sort_direction;
        
        $results = $this->db->get_rows($sql);
        
        return $this->return_instances($results);
    }

    public function find_and_add_new_templates()
    {
        $files     = $this->get_template_files();

        $templates = $this->all();
        $cache     = array();
        
        if (PerchUtil::count($templates)) {
            foreach($templates as $Template) {
                $cache[] = $Template->templatePath();
            }
        }
        
        if (PerchUtil::count($files)) {
            foreach($files as $file) {
                if (!in_array($file['path'], $cache)) {
                    // template is new
                    $data = array();
                    $data['templateTitle'] = $file['label'];
                    $data['templatePath']  = $file['path'];
                    $data['optionsPageID'] = '0';
                    $this->create($data);
                }
            }
        }
    }


    public function get_templates($template_ids=false)
    {
        $a = array();

        $templates = $this->get_filtered($template_ids);
        

        if (PerchUtil::count($templates)) {
            foreach($templates as $Template) {

                $segments = explode('/', $Template->templatePath());

                if (count($segments)==1) {
                    $a[PerchLang::get('General')][] = array('id'=>$Template->id(), 'label'=>$Template->templateTitle());
                }else{
                    $file = array_pop($segments);
                    $tmp = array('id'=>$Template->id(), 'label'=>$Template->templateTitle());
                    $target = &$a;
                    for($i=0; $i<count($segments); $i++) {
                        $label = $this->template_display_name($segments[$i]);
                        if (!isset($a[$label])){
                            $a[$label]=array();
                        }
                        $target = &$a[$label];
                    }
                    $target[] = $tmp;
                }

            }
        }
        return $a;
    }

    private function get_template_files($path=false, $include_hidden=false, $initial_path=false)
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
                        if ($extension == 'php' || $extension == str_replace('.', '', PERCH_DEFAULT_EXT)) {
                            $p = str_replace($initial_path, '', $path);
                            if (!$p) {
                                $a[] = array('filename'=>$file, 'path'=>$file, 'label'=>$this->template_display_name($file), 'group'=>'general', 'sort'=>'0_'.$file);
                            }else{
                                $out_path = ltrim($p, '/').'/'.$file;
                                $a[] = array('filename'=>$file, 'path'=>$out_path, 'label'=>$this->template_display_name($file), 'group'=>trim($p,'/'), 'sort'=>'x_'.$out_path);
                            }
                        }else{
                            $a = array_merge($a, $this->get_template_files($path.'/'.$file, $include_hidden, $initial_path));
                        }
                    }
                }
                closedir($dh);
            }
            if (PerchUtil::count($a)) $a = PerchUtil::array_sort($a, 'sort');
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
