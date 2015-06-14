<?php

class PerchCategories_Sets extends PerchFactory
{
	protected $singular_classname = 'PerchCategories_Set';
	protected $table              = 'category_sets';
	protected $pk                 = 'setID';
	protected $namespace 		  = 'category';
	protected $event_prefix       = 'category';
	
	protected $default_sort_column  = 'setTitle';  

	public $static_fields   = array('setTitle', 'setSlug', 'setTemplate', 'setCatTemplate');


	public function get_templates($path=false, $include_hidden=false, $initial_path=false)
	{
	    $Perch = Perch::fetch();
	    
	    if ($path===false) $path = PERCH_TEMPLATE_PATH.'/categories';
	    if ($initial_path===false) $initial_path = $path;
	    $a = array();
	    if (is_dir($path)) {
	        if ($dh = opendir($path)) {
	            while (($file = readdir($dh)) !== false) {
	                if(substr($file, 0, 1) != '.' && ($include_hidden || substr($file, 0, 1) != '_') && !preg_match($Perch->ignore_pattern, $file)) {
	                    $extension = PerchUtil::file_extension($file);
	                    if ($extension == 'html' || $extension == 'htm') {
	                        $p = str_replace($initial_path, '', $path);
	                        if (!$p) {
	                            $a[PerchLang::get('Categories')][] = array('filename'=>$file, 'path'=>$file, 'label'=>$this->template_display_name($file));
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


	public function template_display_name($file_name)
	{
	    if (substr($file_name, 0, 1) == '_') {
	        $file_name = '*'.$file_name;
	    }

	    $file_name = str_replace('.html', '', $file_name);
	    $file_name = str_replace('_', ' ', $file_name);
	    $file_name = str_replace('-', ' - ', $file_name);
	    $file_name = str_replace('/', ' / ', $file_name);
	    
	    $file_name = ucwords($file_name);
	    
	    return $file_name;
	}

	public static function get_settings_select_list($Form, $id, $details, $setting)
	{
		$opts = array();
		$opts[] = array('value'=>'', 'label'=>'');
		$c = __CLASS__;
		$Sets = new $c;
		$sets = $Sets->all();
		if (PerchUtil::count($sets)) {
			foreach($sets as $Set) {
				$opts[] = array('value'=>$Set->id(), 'label'=>$Set->setTitle());
			}
		}
        return $Form->select($id, $opts, $Form->get($details, $id, $setting['default'])); 
	}
}