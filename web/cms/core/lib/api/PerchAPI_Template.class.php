<?php

class PerchAPI_Template
{
    public $app_id = false;
    public $version = 1.0;
    
    public $file = false;

    private $Lang = false;
    
    private $Template = false;
    
    public $namespace = false;

    function __construct($version=1.0, $app_id, $Lang)
    {
        $this->app_id  = $app_id;
        $this->version = $version;
        $this->Lang    = $Lang;
        
    }

    public function set($file, $namespace, $default_fields=false)
    {    
        $Perch = Perch::fetch(); // called to make sure constants are defined.

        if ($file && substr($file, -5)!=='.html') $file .= '.html';

        $this->namespace = $namespace;
        
        if (strpos($file, '~')!==false) {
            $local_file = PerchUtil::file_path(PERCH_PATH.'/addons/apps/'.substr($file, strpos($file, '~')+1));
            $user_file  = PerchUtil::file_path(PERCH_TEMPLATE_PATH.substr($file, strpos($file, 'templates')+9));
        }else{
            $local_file = PerchUtil::file_path(PERCH_PATH.'/addons/apps/'.$this->app_id.'/templates/'.$file);
            $user_file  = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/'.$file);
        }

        if (file_exists($user_file)) {
            $template_file = $user_file;
        }else{
            $template_file = $local_file;
        }

        $this->Template = new PerchTemplate($template_file, $namespace, $relative_path=false);    
        $this->Template->enable_encoding();
        $this->Template->apply_post_processing = true;

        if ($default_fields && $this->Template) {
            $this->Template->append($default_fields);
        }

        $this->file = $this->Template->file;

        return $this->Template->status;
    }
    
    public function set_from_string($str_template, $namespace)
    {    
        $Perch = Perch::fetch(); // called to make sure constants are defined.

        $this->namespace = $namespace;
        
        $this->Template = new PerchTemplate(false, $namespace, $relative_path=false);    
        $this->Template->set_template('__STRING__');
        $this->Template->load($str_template);
        $this->Template->enable_encoding();
        $this->Template->apply_post_processing = true;

        $this->file = $this->Template->file;

        return $this->Template->status;
    }

    public function render($data)
    {
        return $this->Template->render($data);
    }

    public function render_group($data, $implode=true)
    {
        return $this->Template->render_group($data, $implode);
    }
    
    public function find_all_tags($namespace=false)
    {
        if ($namespace==false) {
            $namespace = $this->namespace;
        }
        
        return $this->Template->find_all_tags($namespace);
    }
    
    public function find_all_tag_ids($namespace=false)
    {
        if ($namespace==false) {
            $namespace = $this->namespace;
        }
        
        return $this->Template->find_all_tag_ids($namespace);
    }

    public function find_tag($tag)
	{
		return $this->Template->find_tag($tag);
	}
    
    public function find_help($id=null)
    {
        return $this->Template->find_help($id);
    }
    
    public function apply_runtime_post_processing($html, $vars=array())
    {
        if (!$this->Template) {
            $this->Template = new PerchTemplate(); 
        }
        
        return $this->Template->apply_runtime_post_processing($html, $vars);
    }

    public function use_noresults()
    {
        return $this->Template->use_noresults();
    }

    public function append($string, $parse_includes=false)
    {
        return $this->Template->append($string, $parse_includes);
    }

    public function find_all_tags_and_repeaters($type=false, $contents=false)
    {
        if ($type==false) $type = $this->namespace;
        return $this->Template->find_all_tags_and_repeaters($type, $contents);
    }

    public function get_block_tags($type)
    {
        return $this->Template->get_block_tags($type);
    }

    public function disable_feature($feature)
    {
        return $this->Template->disable_feature($feature);
    }

    public function get_field_type_map($type='content')
    {
        return $this->Template->get_field_type_map($type);
    }

}