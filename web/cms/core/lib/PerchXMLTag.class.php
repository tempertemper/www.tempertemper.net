<?php
    
    class PerchXMLTag
    {
        public $attributes = [];
        public $data_attributes = [];
        private $tag;
        
        public function __construct($tag)
        {
            $this->tag = $tag;
            $this->parse();
        }
        
        public function __get($property)
        {
            if (isset($this->attributes[$property])) {
                return $this->attributes[$property];
            }
            
            return false;
        }
        
        public function __call($method, $arguments = false)
        {
            
            if (isset($this->attributes[$method])) {
                return $this->attributes[$method];
            }
            
            // If attribute is not set, return the first argument as the default value
            if (isset($arguments[0])) {
                return $arguments[0];
            }
            
            return false;
        }
        
        private function parse()
        {
            $pattern = '{([a-z-]*)="([^"]*)"}';
            
            // Do we have escaped quotes? If so, use heavier rexexp
            if (strpos($this->tag, '\"')) {
                # http://ad.hominem.org/log/2005/05/quoted_strings.php - Thanks, Trent!
                $pattern = '{([a-z-]+)=[\"]([^\"\\\\]*(?:\\\\.[^\"\\\\]*)*)[\"]}';
            }
            
            $count = preg_match_all($pattern, $this->tag, $matches, PREG_SET_ORDER);
            
            if ($count > 0 && is_array($matches)) {
                foreach ($matches as $match) {
                    if ($match[2] == 'false') {
                        $val = false;
                    } else {
                        $val = str_replace('\"', '"', $match[2]);
                    }
                    $key = str_replace('-', '_', $match[1]);
                    
                    $this->attributes[$key] = $val;
                    if (substr($match[1], 0, 5) == 'data-') {
                        $this->data_attributes[$match[1]] = $val;
                    }
                }
            }
        }
        
        public function get_attributes()
        {
            return $this->attributes;
        }
        
        public function get_data_attribute_string()
        {
            if (PerchUtil::count($this->data_attributes)) {
                $out = [];
                foreach ($this->data_attributes as $key => $val) {
                    $out[] = $key . '="' . PerchUtil::html($val, true) . '"';
                }
                if (count($out)) {
                    return implode(' ', $out);
                }
            }
            
            return false;
        }

        public function remap_attributes($search_prefix, $replace_prefix)
        {
            if (PerchUtil::count($this->attributes)) {
                $l = strlen($search_prefix);
                foreach($this->attributes as $key=>$val) {
                    if (substr($key, 0, $l) == $search_prefix) {
                        $this->attributes[$replace_prefix.substr($key, $l)] = $val;
                    }
                }  
            }
        }
        
        public function set($key = null, $val = false)
        {
            if ($key === null) {
                // this could be looking for the 'set' attribute, not trying to set an attribute.
                return $this->__call('set');
            }
            
            $this->attributes[$key] = $val;
        }

        public function set_bulk($atts)
        {
            $this->attributes = array_merge($this->attributes, $atts);
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

        public function get_namespace()
        {
            $tag = $this->tag_name();
            return str_replace('perch:', '', $tag);
        }
        
        public function search_attributes_for($str)
        {
            $str = str_replace('-', '_', $str);
            
            $out = [];
            
            if (PerchUtil::count($this->attributes)) {
                foreach ($this->attributes as $key => $val) {
                    if (strpos($key, $str) === 0) { // beginning of string
                        $out[str_replace('_', '-', $key)] = $val;
                    }
                }
            }
            
            return $out;
        }

        public function get_original_tag_string()
        {
            return $this->tag;
        }
        
        public static function create($name, $type, $attrs = [], $dont_escape = [], $allow_empty = [])
        {
            if ($type != 'closing' && PerchUtil::count($attrs)) {
                $attpairs = [];
                foreach ($attrs as $key => $val) {
                    if (in_array($key, $allow_empty) || $val != '') {
                        if (in_array($key, $dont_escape)) {
                            $attpairs[] = PerchUtil::html($key) . '="' . $val . '"';
                        } else {
                            $attpairs[] = PerchUtil::html($key) . '="' . PerchUtil::html($val, true) . '"';
                        }
                    }
                }
                $attstring = ' ' . implode(' ', $attpairs);
            } else {
                $attstring = '';
            }
            
            $result = '';
            
            switch ($type) {
                case 'opening':
                    $result = '<' . PerchUtil::html($name) . $attstring . '>';
                    break;
                
                case 'single':
                    if (defined('PERCH_XHTML_MARKUP') && PERCH_XHTML_MARKUP == false) {
                        $result = '<' . PerchUtil::html($name) . $attstring . '>';
                    } else {
                        $result = '<' . PerchUtil::html($name) . $attstring . ' />';
                    }
                    break;

                case 'template':
                    $result = '<' . PerchUtil::html($name) . $attstring . ' />';
                    break;
                
                case 'closing':
                    $result = '</' . PerchUtil::html($name) . '>';
                    break;
            }
            
            return $result;
        }
    }
