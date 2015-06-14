<?php

class PerchXMLTag
{
	public $attributes = array();
	public $data_attributes = array();
	private $tag;
	
	function __construct($tag) 
	{
		$this->tag	= $tag;
		$this->parse();
	}
	
	private function parse()
	{
		# http://ad.hominem.org/log/2005/05/quoted_strings.php - Thanks, Trent!
		$count	= preg_match_all('{([a-z-]+)=[\"]([^\"\\\\]*(?:\\\\.[^\"\\\\]*)*)[\"]}', $this->tag, $matches, PREG_SET_ORDER);
	
		if ($count > 0) {
			foreach($matches as $match) {
				if ($match[2]=='false'){
					$val = false;
				}else{
					$val = str_replace('\"', '"', $match[2]);
				}
				$key = str_replace('-', '_', $match[1]);
				
				$this->attributes[$key] = $val;
				if (substr($match[1], 0, 5)=='data-') $this->data_attributes[$match[1]] = $val;
			}
		}
	}
	
	function get_attributes()
	{
		return $this->attributes;
	}
	
	function get_data_attribute_string()
	{
		if (PerchUtil::count($this->data_attributes)) {
			$out = array();
			foreach($this->data_attributes as $key=>$val) {
				$out[] = $key.'="'.PerchUtil::html($val, true).'"';	
			}
			if (count($out)) return implode(' ', $out);
		}
		return false;
	}

	function __get($property) {
		if (isset($this->attributes[$property])) {
			return $this->attributes[$property];
		}
		return false;
	}

	function __call($method, $arguments=false)
	{    
        
		if (isset($this->attributes[$method])) {
			return $this->attributes[$method];
		}

		// if not set, return arg[0] as the default value
		if (isset($arguments[0])) return $arguments[0];

		return false;
	}
	
	public function set($key=false, $val=false)
	{
		if ($key===false) {
			// this could be looking for the 'set' attribute, not trying to set an attribute.
			return $this->__call('set');
		}

	    $this->attributes[$key] = $val;
	}
	
	public function is_set($key)
	{
	    return isset($this->attributes[$key]);
	}

	public function tag_name()
	{
		$parts = explode(' ', $this->tag);
		return str_replace('<', '', $parts[0]);
	}

	public function search_attributes_for($str)
	{
		$str = str_replace('-', '_', $str);

		$out = array();

		if (PerchUtil::count($this->attributes)) {
			foreach($this->attributes as $key=>$val) {
				if (strpos($key, $str)===0) { // beginning of string
					$out[str_replace('_', '-', $key)] = $val;
				}
			}
		}
		return $out;
	}

    public static function create($name, $type, $attrs=array(), $dont_escape=array(), $allow_empty=array())
    {    
        if ($type!='closing' && PerchUtil::count($attrs)) {
            $attpairs = array();
            foreach($attrs as $key=>$val) {
                if (in_array($key, $allow_empty) || $val!='') {
                	if (in_array($key, $dont_escape)) {
                		$attpairs[] = PerchUtil::html($key).'="'.$val.'"';
                	}else{
                		$attpairs[] = PerchUtil::html($key).'="'.PerchUtil::html($val, true).'"';
                	}
                } 
            }
            $attstring = ' '.implode(' ', $attpairs);
        }else{
            $attstring = '';
        }
        
        switch($type) {
            case 'opening':
                return '<'.PerchUtil::html($name).$attstring.'>';
                break;
                
            case 'single':
                if (defined('PERCH_XHTML_MARKUP') && PERCH_XHTML_MARKUP==false) {
                    return '<'.PerchUtil::html($name).$attstring.'>';
                }else{
                    return '<'.PerchUtil::html($name).$attstring.' />';
                }
                break;

            case 'closing':
                return '</'.PerchUtil::html($name).'>';
                break;
        }
        
        return '';
    }
}
