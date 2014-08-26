<?php

class PerchFieldTypes 
{
    private static $_seen = array();
    
    public static function get($type, $Form, $Tag, $all_tags=false, $app_id='content')
    {
        $r = false;
        
        if (!$type) {
            $tag_name = $Tag->tag_name();
            if ($tag_name=='perch:categories') {
                $type = 'category';
            }else{
                $type = 'text';
            }
        }

        $classname = 'PerchFieldType_'.$type;
        
        if (class_exists($classname, false)){
            $r = new $classname($Form, $Tag, $app_id);
            if (!in_array($classname, self::$_seen)) {
                $Perch = Perch::fetch();
                if ($Perch->admin) {
                    if ($all_tags) $r->set_sibling_tags($all_tags);
                    $r->add_page_resources();
                }
                
                self::$_seen[] = $classname;
            }
        
        }else{    
            $path = PerchUtil::file_path(PERCH_PATH.'/addons/fieldtypes/'.$type.'/'.$type.'.class.php');
            if (file_exists($path)) {
                include($path);
                $r =  new $classname($Form, $Tag, $app_id);

                $Perch = Perch::fetch();
                if ($Perch->admin) {
                    if ($all_tags) $r->set_sibling_tags($all_tags);
                    $r->add_page_resources();
                }
            }         
        }
                
        if (!is_object($r)) {
            $r = new PerchFieldType($Form, $Tag, $app_id);
        }
        
        if ($all_tags) {
            $r->set_sibling_tags($all_tags);
        }
        
        return $r;
    }
}

/* ---------------------------- DEFAULT FIELD TYPES ---------------------------- */

/* ------------ TEXT ------------ */

class PerchFieldType_text extends PerchFieldType 
{
}

/* ------------ HIDDEN ------------ */

class PerchFieldType_hidden extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return '';
    }
}

/* ------------ URL ------------ */

class PerchFieldType_url extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return $this->Form->url($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $this->Tag->size(), $this->Tag->maxlength());
    }
}

/* ------------ COLOR ------------ */

class PerchFieldType_color extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return $this->Form->text($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $this->Tag->size(), $this->Tag->maxlength(), 'color');
    }
}


/* ------------ EMAIL ------------ */

class PerchFieldType_email extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return $this->Form->email($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $this->Tag->size(), $this->Tag->maxlength());
    }
}

/* ------------ SEARCH ------------ */

class PerchFieldType_search extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return $this->Form->search($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $this->Tag->size(), $this->Tag->maxlength());
    }
}

/* ------------ DATE ------------ */

class PerchFieldType_date extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        if ($this->Tag->native()) {
            if ($this->Tag->time()) {
                if ($this->Tag->time()=='local'){
                    $ftype = 'datetime-local'; 
                    $format = 'Y-m-d\TH:i';
                }else{
                    $ftype = 'datetime';
                    $format = 'Y-m-d\TH:i\Z';
                }

                if (isset($details[$this->Tag->id()])) {
                    $details[$this->Tag->id()] = date($format, strtotime($details[$this->Tag->id()]));
                }

                return $this->Form->text($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $this->Tag->size(), $this->Tag->maxlength(), $ftype);
            }else{
                if (isset($details[$this->Tag->id()])) {
                    $details[$this->Tag->id()] = date('Y-m-d', strtotime($details[$this->Tag->id()]));
                }
                return $this->Form->text($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $this->Tag->size(), $this->Tag->maxlength(), 'date');
            }
        }else{
            $field_order = 'dmy';
            if ($this->Tag->fieldorder()) {
                $field_order = $this->Tag->fieldorder();
            }

            if ($this->Tag->time()) {
                return $this->Form->datetimepicker($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $field_order, $this->Tag->allowempty());
            }else{
                return $this->Form->datepicker($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $field_order, $this->Tag->allowempty());
            }
        }
    }
    
    public function get_raw($post=false, $Item=false)
    {
        $id = $this->Tag->id();

        if ($this->Tag->native()) {
            if ($post===false) {
                $post = $_POST;
            }
            
            if (isset($post[$id])) {
                $raw = trim(stripslashes($post[$id]));
                $this->raw_item = date('Y-m-d H:i:s', strtotime($raw));
            }

        }else{
            $this->raw_item = $this->Form->get_date($id, $post);
        }

        
        return $this->raw_item;
    }
    
    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        return strftime('%A %d %B %Y', strtotime($raw));
    }
}


/* ------------ TIME ------------ */

class PerchFieldType_time extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return $this->Form->timepicker($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()));
    }
    
    public function get_raw($post=false, $Item=false)
    {
        $id = $this->Tag->id();
        $this->raw_item = $this->Form->get_date($id, $post);
        return $this->raw_item;
    }
}


/* ------------ SLUG ------------ */

class PerchFieldType_slug extends PerchFieldType
{
    public function add_page_resources()
    {
        $Perch = Perch::fetch();
        $Perch->add_javascript(PERCH_LOGINPATH.'/core/assets/js/slugs.js');
    }

    public function render_inputs($details=array())
    {
        if ($this->Tag->editable()) {

            $s = '';
            $attrs = '';

            $id = $this->Tag->id();
            $value = $this->Form->get($details, $id, $this->Tag->default(), $this->Tag->post_prefix());

            if (!$value) {
                if (isset($details[$id])) {
                    $value = $details[$id];
                }
            }

            if ($value && $this->Tag->indelible()) {
                $attrs .= 'disabled="disabled" ';
            }else{

                if ($this->Tag->for()) {
                    $parts = $this->break_for_string($this->Tag->for());
                    $tmp = array();
                    if (PerchUtil::count($parts)) {
                        foreach($parts as $field) {
                            $tmp[] = $this->Tag->post_prefix().$field;
                        }
                    }

                    if (!$value) $attrs .= 'data-slug-for="'.PerchUtil::html(implode(' ',$tmp), true).'"';
                }

            }          

            $s = $this->Form->text($this->Tag->input_id(), $value, $this->Tag->size(), $this->Tag->maxlength(), 'text', $attrs);
                    
            return $s;
        }

        return '';
    }
    
    public function get_raw($post=false, $Item=false)
    {

        $value = false;

        if ($post===false) {
            $post = $_POST;
        }
        
        $id = $this->Tag->id();
        if (isset($post[$id])) {
            $this->raw_item = trim(stripslashes($post[$id]));
            $value = $this->raw_item;
        }

        // Indelible?
        if ($this->Tag->indelible()) {
            // if it's indelible, just return the previous value.

            $prev_value = false;

            if (is_object($Item)){        
                $json = PerchUtil::json_safe_decode($Item->itemJSON(), true);

                if (PerchUtil::count($json) && isset($json[$this->Tag->id()])) {
                    $prev_value = $json[$this->Tag->id()];
                }
            }elseif (is_array($Item)) {
                $json = $Item;
                if (PerchUtil::count($json) && isset($json[$this->Tag->id()])) {
                    $prev_value = $json[$this->Tag->id()];
                }
            }

            if ($prev_value) return $prev_value;
        }

        // Editable + value?
        if ($this->Tag->editable() && $value) {
            // return the user's value
            return $value;
        }

        if ($this->Tag->for()) {

            $parts = $this->break_for_string($this->Tag->for());
            if (PerchUtil::count($parts)) {
                $str = array();
                foreach($parts as $part) {
                    if (isset($post[$part])) {
                        $str[] = trim(stripslashes($post[$part]));
                    }
                }
                return PerchUtil::urlify(implode(' ', $str));
            }

            if (isset($post[$this->Tag->for()])) {
                return PerchUtil::urlify(trim(stripslashes($post[$this->Tag->for()])));
            }
        }
        
        return '';
    }
    
    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        $parts = explode('-', $raw);
        return implode(' ', $parts);
    }

    private function break_for_string($for)
    {
        return explode(' ', $for);
    }
}


/* ------------ TEXTAREA ------------ */

class PerchFieldType_textarea extends PerchFieldType
{
    public function add_page_resources()
    {
        $siblings = $this->get_sibling_tags();

        if (is_array($siblings)) {
            $Perch = Perch::fetch();
            $seen_editors = array();
            foreach($siblings as $tag) {
                if ($tag->editor() && !in_array($tag->editor(), $seen_editors)) {
                    $dir = PerchUtil::file_path(PERCH_PATH.'/addons/plugins/editors/'.$tag->editor());
                    $file = PerchUtil::file_path($dir.'/_config.inc');
                    if (is_dir($dir) && is_file($file)) {
                        $contents = file_get_contents($file);
                        $contents = str_replace('PERCH_LOGINPATH', PERCH_LOGINPATH, $contents);
                        $config = 'default';
                        if ($tag->editor_config()) $config = $tag->editor_config();
                        $contents = str_replace('PERCH_EDITOR_CONFIG', $config, $contents);
                        $Perch->add_foot_content($contents);
                        $seen_editors[] = $tag->editor();
                    }else{
                        PerchUtil::debug('Editor requested, but not installed: '.$this->Tag->editor(), 'error');
                    }
                }
            }
        }else{
            if ($this->Tag->editor()) {
            
                $dir = PerchUtil::file_path(PERCH_PATH.'/addons/plugins/editors/'.$this->Tag->editor());
                $file = PerchUtil::file_path($dir.'/_config.inc');
                if (is_dir($dir) && is_file($file)) {
                    $Perch = Perch::fetch();

                    $Perch->add_foot_content(str_replace('PERCH_LOGINPATH', PERCH_LOGINPATH, file_get_contents($file)));
                }else{
                    PerchUtil::debug('Editor requested, but not installed: '.$this->Tag->editor(), 'error');
                }

            } 
        }
    }

    public function render_inputs($details=array())
    {
        $classname = 'large autowidth ';
        if ($this->Tag->editor())     $classname .= $this->Tag->editor();
        if ($this->Tag->textile())    $classname .= ' textile';
        if ($this->Tag->markdown())   $classname .= ' markdown';
        if ($this->Tag->size())       $classname .= ' '.$this->Tag->size();
        if (!$this->Tag->textile() && !$this->Tag->markdown() && $this->Tag->html()) $classname .= ' html';
        
        $data_atrs = array();
        if ($this->Tag->imagewidth())           $data_atrs['width']   = $this->Tag->imagewidth();
        if ($this->Tag->imageheight())          $data_atrs['height']  = $this->Tag->imageheight();
        if ($this->Tag->imagecrop())            $data_atrs['crop']    = $this->Tag->imagecrop();
        if ($this->Tag->imageclasses())         $data_atrs['classes'] = $this->Tag->imageclasses();
        if ($this->Tag->imagequality())         $data_atrs['quality'] = $this->Tag->imagequality();
        if ($this->Tag->is_set('imagesharpen')) $data_atrs['sharpen'] = $this->Tag->imagesharpen();
        if ($this->Tag->imagedensity())         $data_atrs['density'] = $this->Tag->imagedensity();
        if ($this->Tag->bucket())               $data_atrs['bucket']  = $this->Tag->bucket();

        if ($this->Tag->editor_config())  $data_atrs['editor-config'] = $this->Tag->editor_config();        
        
        if (isset($details[$this->Tag->input_id()]) && $details[$this->Tag->input_id()]!='') {
            $data = $details[$this->Tag->input_id()];        
            if (is_array($data)) {
                $details = array($this->Tag->id()=>$data['raw']);
            }   
        }
    
        $s = $this->Form->textarea($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $classname, $data_atrs);
        $s .= '<div class="clear"></div>';
        
        return $s;
    }
    
    public function get_raw($post=false, $Item=false)
    {
        if ($post===false) {
            $post = $_POST;
        }
        
        $id = $this->Tag->id();
        if (isset($post[$id])) {
            $raw = trim($post[$id]);
            
            $raw = stripslashes($raw);
            $value = $raw;
            
            $formatting_language_used = false;

            // Strip HTML by default
            if (!is_array($value) && PerchUtil::bool_val($this->Tag->html()) == false) {
                $value = PerchUtil::html($value);
                $value = strip_tags($value);
            }

            // Textile
            if (!$formatting_language_used && PerchUtil::bool_val($this->Tag->textile()) == true) {

                if (!class_exists('\\Netcarver\\Textile\\Parser', false) && class_exists('Textile', true)) { 
                    // sneaky autoloading hack 
                }
                
                if (PERCH_HTML5) {
                    $Textile = new \Netcarver\Textile\Parser('html5');
                }else{
                    $Textile = new \Netcarver\Textile\Parser;
                }
                

                if (PERCH_RWD) {
                    $value  =  $Textile->setDimensionlessImages(true)->textileThis($value);
                }else{
                    $value  =  $Textile->textileThis($value);
                }
                
                if (defined('PERCH_XHTML_MARKUP') && PERCH_XHTML_MARKUP==false) {
        		    $value = str_replace(' />', '>', $value);
        		}

                $formatting_language_used = true;
            }

            // Markdown
            if (!$formatting_language_used && PerchUtil::bool_val($this->Tag->markdown()) == true) {
                // Fix markdown blockquote syntax - > gets encoded.
                $value = preg_replace('/[\n\r]&gt;\s/', "\n> ", $value);
                
                // Fix autolink syntax
                $value = preg_replace('#&lt;(http[a-zA-Z0-9-\.\/:]*)&gt;#', "<$1>", $value);
                
                $Markdown = new ParsedownExtra();
                $value = $Markdown->text($value);

                if (!class_exists('\\Michelf\\SmartyPants', false) && class_exists('SmartyPants', true)) { 
                    // sneaky autoloading hack 
                }

                $SmartyPants = new \Michelf\SmartyPants;
                $value = $SmartyPants->transform($value);
                if (PERCH_HTML_ENTITIES==false) {
                    $value = html_entity_decode($value, ENT_NOQUOTES, 'UTF-8');    
                }
                
                $formatting_language_used = true;
            }else{


                // Smartypants without Markdown (MD gets it by default)

                if (PerchUtil::bool_val($this->Tag->smartypants()) == true) {

                    if (!class_exists('\\Michelf\\SmartyPants', false) && class_exists('SmartyPants', true)) { 
                        // sneaky autoloading hack 
                    }

                    $SmartyPants = new \Michelf\SmartyPants;

                    $value = $SmartyPants->transform($value);
                    if (PERCH_HTML_ENTITIES==false) {
                        $value = html_entity_decode($value, ENT_NOQUOTES, 'UTF-8');    
                    }
                }

            }


            
            
            
            $store = array(
                'raw' => $raw,
                'processed' => $value
            );
            
            $this->raw_item = $store;
        
            return $this->raw_item;
        }
        
        return null;
    }
    
    public function get_processed($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        $value = $raw;
        
        if (is_array($value)) {
            if (isset($value['processed'])) {
                $this->processed_output_is_markup = true;
                return $value['processed'];
            }
            
            if (isset($value['raw'])) {
                return $value['raw'];
            }
        }else{
            if (!strpos($this->Tag->id(),'HTML')) {
                $value = $this->get_raw(array($this->Tag->id()=>$value));
                return $value['processed'];    
            }
            
        }


            
        return $value;
    }
      
    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        if (is_array($raw)) {
            
            if (isset($raw['processed'])) {
                return strip_tags($raw['processed']);
            }
                    
            if (isset($raw['raw'])) {
                return $raw['raw'];
            }
            
        }
        
        return $raw;
    }
}

/* ------------ CHECKBOX ------------ */

class PerchFieldType_checkbox extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        $val = ($this->Tag->value() ? $this->Tag->value() : '1');
        return $this->Form->checkbox($this->Tag->input_id(), $val, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()));
    }
}

/* ------------ SELECT ------------ */

class PerchFieldType_select extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        $options = explode(',', $this->Tag->options());
        $opts = array();
        if (PerchUtil::bool_val($this->Tag->allowempty())== true) {
            $opts[] = array('label'=>'', 'value'=>'');
        }
        if (PerchUtil::count($options) > 0) {
            foreach($options as $option) {
                $val = trim($option);
                $label = $val;
                if (strpos($val, '|')!==false) {
                    $parts = explode('|', $val);
                    $label = $parts[0];
                    $val   = $parts[1];
                }
                $opts[] = array('label'=>$label, 'value'=>$val);
            }
        }
        return $this->Form->select($this->Tag->input_id(), $opts, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()));
    }
}


/* ------------ RADIO ------------ */

class PerchFieldType_radio extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        $s = '';
        $options = explode(',', $this->Tag->options());
        if (PerchUtil::count($options) > 0) {
            $k = 0;
            foreach($options as $option) {
                $val    = trim($option);
                $label  = $val;
                if (strpos($val, '|')!==false) {
                    $parts = explode('|', $val);
                    $label = $parts[0];
                    $val   = $parts[1];
                }
                $id  = $this->Tag->input_id() . $k;
                $s .= '<span class="radio">';
                $s .= $this->Form->radio($id, $this->Tag->input_id(), $val, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()));
                $this->Form->disable_html_encoding();
                $s .= $this->Form->label($id, $label, 'radio', false, false);
                $this->Form->enable_html_encoding();
                $s .= '</span>';
                $k++;
            }
        }
        
        return $s;
    }
}


/* ------------ IMAGE ------------ */

class PerchFieldType_image extends PerchFieldType
{
    public static $file_paths = array();
    
    public function render_inputs($details=array())
    {
        $Perch = Perch::fetch();
        $bucket = $Perch->get_resource_bucket($this->Tag->bucket());

        $PerchImage = new PerchImage;
        $s = $this->Form->image($this->Tag->input_id());
        $s .= $this->Form->hidden($this->Tag->input_id().'_field', '1');

        $assetID     = false;
        $asset_field = $this->Tag->input_id().'_assetID';

        if (isset($details[$this->Tag->input_id()]['assetID'])) {
            $assetID = $details[$this->Tag->input_id()]['assetID'];
        }

        $s .= $this->Form->hidden($asset_field, $this->Form->get(array(), $asset_field, ''));      
       

        PerchUtil::initialise_resource_bucket($bucket);

        if (!is_writable($bucket['file_path'])) {
            $s .= $this->Form->hint(PerchLang::get('Your resources folder is not writable. Make this folder (') . PerchUtil::html($bucket['web_path']) . PerchLang::get(') writable to upload images.'), 'error');
        }  


        if (isset($details[$this->Tag->input_id()]) && $details[$this->Tag->input_id()]!='') {
            $json = $details[$this->Tag->input_id()];

            //PerchUtil::debug($json);

            if (isset($json['bucket'])) {
                $bucket = $Perch->get_resource_bucket($json['bucket']);
            }    

            if (isset($json['sizes']['thumb'])) {
                $image_src  = $json['sizes']['thumb']['path'];
                $image_w    = $json['sizes']['thumb']['w'];
                $image_h    = $json['sizes']['thumb']['h'];
            }else{
                // For items imported from previous version
                if (is_string($json)) {
                    $image_src = str_replace(PERCH_RESPATH, '', $PerchImage->get_resized_filename($json, 150, 150, 'thumb'));    
                    $image_w   = '150';
                    $image_h   = '150';
                }else{
                    $image_src = false;
                    $image_w   = '';
                    $image_h   = '';
                }
                
                
            }
          
            $image_path = false;
            if ($image_src) $image_path = PerchUtil::file_path($bucket['file_path'].'/'.$image_src);


            if (file_exists($image_path)) {

                $s .= '<div class="asset-badge" data-for="'.$asset_field.'">';

                $variant_key = 'w'.$this->Tag->width().'h'.$this->Tag->height().'c'.($this->Tag->crop() ? '1' : '0').($this->Tag->density() ? '@'.$this->Tag->density().'x': '');

                $variant = (isset($json['sizes'][$variant_key]) ? $json['sizes'][$variant_key] : false);

                $s .= '<div class="asset-badge-thumb">';
                $s .= '<img src="'.PerchUtil::html($bucket['web_path'].'/'.$image_src).'" width="'.$image_w.'" height="'.$image_h.'" alt="Preview" />';
                $s .= '</div>';

                $s .= '<div class="asset-badge-meta">';

                if (!$this->Tag->is_set('app_mode')) {
                    
                    $s .= '<div class="asset-badge-remove">';
                    $s .= $this->Form->label($this->Tag->input_id().'_remove', PerchLang::get('Remove'), 'inline'). ' ';
                    $s .= $this->Form->checkbox($this->Tag->input_id().'_remove', '1', 0);
                    $s .= '</div>';
                    if ($variant) {
                        $s .= '<ul class="meta">';
                        $s .= '<li class="title">'.(isset($json['title']) ? $json['title'] : $variant['path']).'</li>';
                        $s .= '<li>'.ucfirst(str_replace('/', ' / ', $variant['mime'])).'</li>';
                        
                        
                        $size     = floatval($variant['size']);

                        if ($size < 1048576) {
                            $size = round($size/1024, 0).'KB';
                        }else{
                            $size = round($size/1024/1024, 0).'MB';
                        }
                        $s .= '<li>'.$variant['w'].' x '.$variant['h'].' px @ '.$size.'</li>';

                        $s .= '</ul>';
                    }
                    
                } else {

                    if (!class_exists('PerchAssets_Assets', false)) {
                        include_once(PERCH_CORE.'/apps/assets/PerchAssets_Assets.class.php');
                        include_once(PERCH_CORE.'/apps/assets/PerchAssets_Asset.class.php');
                    }

                    $Assets = new PerchAssets_Assets;
                    $Asset = $Assets->find($assetID);

                    if ($Asset) {
                        $s .= '<ul class="meta">';
                        $s .= '<li><a href="'.$Asset->web_path().'">'.PerchLang::get('Download original file').'</a></li>';
                        $s .= '</ul>';
                    }

                } // app_mode

                $s .= '</div>';
                $s .= '</div>';
                
                
            }
            
        }else{
            $s .= '<div class="asset-badge hidden" data-for="'.$asset_field.'">';
            $s .= '</div>';
        }

        
        if (isset($image_path) && file_exists($image_path)) $s .= $this->Form->hidden($this->Tag->input_id().'_populated', '1');

        $type = 'img';
        if ($this->Tag->file_type()) $type = $this->Tag->file_type();
        
        $s .= ' <span class="ft-choose-asset'.($this->Tag->disable_asset_panel() ? ' assets-disabled' : '').'" data-type="'.$type.'" data-field="'.$asset_field.'" data-input="'.$this->Tag->input_id().'" data-app="'.$this->app_id.'" data-app-uid="'.$this->unique_id.'" data-bucket="'.PerchUtil::html($bucket['name'], true).'"></span>';
        return $s;
    }
    
    public function get_raw($post=false, $Item=false) 
    {
        $store = array();

        $Perch = Perch::fetch();
        $bucket = $Perch->get_resource_bucket($this->Tag->bucket());
        
        $image_folder_writable = is_writable($bucket['file_path']);
        
        $item_id = $this->Tag->input_id();
        $asset_reference_used = false;

        $target = false;

        // Asset ID?
        if (isset($post[$this->Tag->id().'_assetID']) && $post[$this->Tag->id().'_assetID']!='') {
            $new_assetID = $post[$this->Tag->id().'_assetID'];

            if (!class_exists('PerchAssets_Assets', false)) {
                include_once(PERCH_CORE.'/apps/assets/PerchAssets_Assets.class.php');
                include_once(PERCH_CORE.'/apps/assets/PerchAssets_Asset.class.php');
            }

            $Assets = new PerchAssets_Assets;
            $Asset = $Assets->find($new_assetID);

            if (is_object($Asset)) {
                $target   = $Asset->file_path();
                $filename = $Asset->resourceFile();

                $store['assetID']  = $Asset->id();
                $store['title']    = $Asset->resourceTitle();
                $store['_default'] = $Asset->web_path();
                $store['bucket']   = $Asset->resourceBucket();

                if ($store['bucket']!=$bucket) {
                    $bucket = $Perch->get_resource_bucket($store['bucket']);
                }

                $asset_reference_used = true;
            }
        }

        if ($image_folder_writable && isset($_FILES[$item_id]) && (int) $_FILES[$item_id]['size'] > 0) {
                       
            if (!isset(self::$file_paths[$this->Tag->id()])) {
            
                $filename = PerchUtil::tidy_file_name($_FILES[$item_id]['name']);
                if (strpos($filename, '.php')!==false) $filename .= '.txt'; // diffuse PHP files              

                $target = PerchUtil::file_path($bucket['file_path'].DIRECTORY_SEPARATOR.$filename);
                if (file_exists($target)) {                                        
                    $dot = strrpos($filename, '.');
                    $filename_a = substr($filename, 0, $dot);
                    $filename_b = substr($filename, $dot);

                    $count = 1;
                    while (file_exists($bucket['file_path'].DIRECTORY_SEPARATOR.PerchUtil::tidy_file_name($filename_a.'-'.$count.$filename_b))) {
                        $count++;
                    }

                    $filename = PerchUtil::tidy_file_name($filename_a . '-' . $count . $filename_b);
                    $target = $bucket['file_path'].DIRECTORY_SEPARATOR.$filename;
            
                }
                                    
                PerchUtil::move_uploaded_file($_FILES[$item_id]['tmp_name'], $target);
                $store['_default'] = rtrim($bucket['web_path'], '/').'/'.$filename;
            }
        }

        if ($target && $filename && is_file($target)) {

            self::$file_paths[$this->Tag->id()] = $target;     
                
            
            $store['path'] = $filename;
            $store['size'] = filesize($target);
            $store['bucket'] = $bucket['name'];

            // Is this an SVG? 
            $svg = false;

            $size = getimagesize($target);
            if (PerchUtil::count($size)) {
                $store['w'] = $size[0];
                $store['h'] = $size[1];
                if (isset($size['mime'])) $store['mime'] = $size['mime'];
            }else{
                $PerchImage = new PerchImage;

                if ($PerchImage->is_webp($target)) {

                    $store['mime'] = 'image/webp';

                }elseif ($PerchImage->is_svg($target)) {
                    $svg = true;
                    $size = $PerchImage->get_svg_size($target);
                    if (PerchUtil::count($size)) {
                        $store['w'] = $size['w'];
                        $store['h'] = $size['h'];
                        if (isset($size['mime'])) $store['mime'] = $size['mime'];
                    }
                }else{
                    // It's not an image (according to getimagesize) and not an SVG.
                    if ($this->Tag->detect_type()) {
                        // if we have permission to guess, our guess is that it's a file.
                        PerchUtil::debug('Guessing file', 'error');
                        $this->Tag->set('type', 'file');
                    }

                    $store['mime'] = PerchUtil::get_mime_type($target);
                }
            }

            // thumbnail
            if ($this->Tag->type()=='image') {
                $PerchImage = new PerchImage;
                $PerchImage->set_density(2);

                $result = false;

                if ($asset_reference_used) {
                    $result = $Assets->get_resize_profile($store['assetID'], 150, 150, false, 'thumb', $PerchImage->get_density());
                }

                if (!$result) $result = $PerchImage->resize_image($target, 150, 150, false, 'thumb');
                if (is_array($result)) {
                    //PerchUtil::debug($result, 'notice');
                    if (!isset($store['sizes'])) $store['sizes'] = array();
                
                    $variant_key = 'thumb';
                    $tmp = array();
                    $tmp['w']        = $result['w'];
                    $tmp['h']        = $result['h'];
                    $tmp['target_w'] = 150;
                    $tmp['target_h'] = 150;
                    $tmp['density']  = 2;
                    $tmp['path']     = $result['file_name'];
                    $tmp['size']     = filesize($result['file_path']);
                    $tmp['mime']     = (isset($result['mime']) ? $result['mime'] : '');   
                                            
                    $store['sizes'][$variant_key] = $tmp;
                }
                unset($result);
                unset($PerchImage);
            }
            if ($this->Tag->type()=='file') {
                $PerchImage = new PerchImage;
                $PerchImage->set_density(2);

                $result = $PerchImage->thumbnail_file($target, 150, 150, false);
                if (is_array($result)) {
                    if (!isset($store['sizes'])) $store['sizes'] = array();
                
                    $variant_key = 'thumb';
                    $tmp = array();
                    $tmp['w']        = $result['w'];
                    $tmp['h']        = $result['h'];
                    $tmp['target_w'] = 150;
                    $tmp['target_h'] = 150;
                    $tmp['density']  = 2;
                    $tmp['path']     = $result['file_name'];
                    $tmp['size']     = filesize($result['file_path']);
                    $tmp['mime']     = (isset($result['mime']) ? $result['mime'] : '');   
                    
                    $store['sizes'][$variant_key] = $tmp;
                }
                unset($result);
                unset($PerchImage);
            }
               
            
        }

        // Loop through all tags with this ID, get their dimensions and resize the images.
        $all_tags = $this->get_sibling_tags();
        
        if (PerchUtil::count($all_tags)) {
            foreach($all_tags as $Tag) {
                if ($Tag->id()==$this->Tag->id()) {
                    // This is either this tag, or another tag in the template with the same ID.
                    
                    if ($Tag->type()=='image' && ($Tag->width() || $Tag->height()) && isset(self::$file_paths[$Tag->id()])) {

                        $variant_key = 'w'.$Tag->width().'h'.$Tag->height().'c'.($Tag->crop() ? '1' : '0').($Tag->density() ? '@'.$Tag->density().'x': '');

                        if (!isset($store['sizes'][$variant_key])) {

                            $PerchImage = new PerchImage;
                            if ($Tag->quality()) $PerchImage->set_quality($Tag->quality());
                            if ($Tag->is_set('sharpen')) $PerchImage->set_sharpening($Tag->sharpen());
                            if ($Tag->density()) $PerchImage->set_density($Tag->density());

                            $result = false;

                            if ($asset_reference_used) {
                                $result = $Assets->get_resize_profile($store['assetID'], $Tag->width(), $Tag->height(), $Tag->crop(), false, $PerchImage->get_density());
                            }

                            if (!$result) $result = $PerchImage->resize_image(self::$file_paths[$Tag->id()], $Tag->width(), $Tag->height(), $Tag->crop());
                            
                            if (is_array($result)) {
                                if (!isset($store['sizes'])) $store['sizes'] = array();
                                                            
                                $tmp             = array();
                                $tmp['w']        = $result['w'];
                                $tmp['h']        = $result['h'];
                                $tmp['target_w'] = $Tag->width();
                                $tmp['target_h'] = $Tag->height();
                                $tmp['crop']     = $Tag->crop();
                                $tmp['density']  = ($Tag->density() ? $Tag->density() : '1');
                                $tmp['path']     = $result['file_name'];
                                $tmp['size']     = filesize($result['file_path']);
                                $tmp['mime']     = (isset($result['mime']) ? $result['mime'] : '');    

                                $store['sizes'][$variant_key] = $tmp;

                                unset($tmp);
                            }

                            unset($result);
                            unset($PerchImage);
                        }
                    }
                }
            }
        }
        

        if (isset($_POST[$item_id.'_remove'])) {
            $store = array();
        }

        // If a file isn't uploaded...
        if (!$asset_reference_used && (!isset($_FILES[$item_id]) || (int) $_FILES[$item_id]['size'] == 0)) {
            // If remove is checked, remove it.
            if (isset($_POST[$item_id.'_remove'])) {
                $store = array();
            }else{
                // Else get the previous data and reuse it.
                if (is_object($Item)){
                    
                    $json = PerchUtil::json_safe_decode($Item->itemJSON(), true);

                    if (PerchUtil::count($json) && $this->Tag->in_repeater() && $this->Tag->tag_context()) {
                        $waypoints = preg_split('/_([0-9]+)_/', $this->Tag->tag_context(), null, PREG_SPLIT_DELIM_CAPTURE);
                        if (PerchUtil::count($waypoints) > 0) {
                            $subject = $json;
                            foreach($waypoints as $waypoint) {
                                if (isset($subject[$waypoint])) {
                                    $subject = $subject[$waypoint];
                                }else{
                                    $subject = false;
                                }
                                $store = $subject;
                            }
                        } 
                    }

                    if (PerchUtil::count($json) && isset($json[$this->Tag->id()])) {
                        $store = $json[$this->Tag->id()];
                    }
                }else if (is_array($Item)) {
                    $json = $Item;
                    if (PerchUtil::count($json) && isset($json[$this->Tag->id()])) {
                        $store = $json[$this->Tag->id()];
                    }
                }
            }                                
        }

        // log resources
        if (PerchUtil::count($store) && isset($store['path'])) {
            $Resources = new PerchResources;

            // Main image
            $parentID = $Resources->log($this->app_id, $store['bucket'], $store['path'], 0, 'orig', false, $store);

            // variants
            if (isset($store['sizes']) && PerchUtil::count($store['sizes'])) {
                foreach($store['sizes'] as $key=>$size) {
                    $Resources->log($this->app_id, $store['bucket'], $size['path'], $parentID, $key, false, $size); 
                }
            }

            // Additional IDs from the session
            if (PerchSession::is_set('resourceIDs')) {
                $ids = PerchSession::get('resourceIDs');
                if (is_array($ids) && PerchUtil::count($ids)) {
                    $Resources->log_extra_ids($ids);
                }
                PerchSession::delete('resourceIDs');
            }
        }
        
        self::$file_paths = array();
        
        return $store;
    }
    
    public function get_processed($raw=false)
    {    
        $json = $raw;
        if (is_array($json)) {
            
            $item = $json;
            $orig_item = $item; // item gets overriden by a variant.
            
            if ($this->Tag->width() || $this->Tag->height()) {
                $variant_key = 'w'.$this->Tag->width().'h'.$this->Tag->height().'c'.($this->Tag->crop() ? '1' : '0').($this->Tag->density() ? '@'.$this->Tag->density().'x': '');
                if (isset($json['sizes'][$variant_key])) {
                    $item = $json['sizes'][$variant_key];
                }
            }           
            
            if ($this->Tag->output() && $this->Tag->output()!='path') {
                switch($this->Tag->output()) {        
                    case 'size':
                        return isset($item['size']) ? $item['size'] : 0; 
                        break;
                        
                    case 'h':
                    case 'height':
                        return isset($item['h']) ? $item['h'] : 0;
                        break;

                    case 'w':
                    case 'width':
                        return isset($item['w']) ? $item['w'] : 0;
                        break;
					
					case 'filename':
						return $item['path'];
						break;

                    case 'mime':
                        return $item['mime'];
                        break;

                    case 'tag':

                        // include inline?
                        if ($this->Tag->include() == 'inline' && isset($item['mime'])) {
                            if (strpos($item['mime'], 'svg')) {
                                $this->processed_output_is_markup = true;
                                return file_get_contents($this->_get_image_file($orig_item, $item));
                                break;
                            }
                        }


                        $attrs = array(
                            'src'=> $this->_get_image_src($orig_item, $item),
                        );

                        if (!PERCH_RWD) {
                            $attrs['width']  = isset($item['w']) ? $item['w'] : '';
                            $attrs['height'] = isset($item['h']) ? $item['h'] : '';
                        }

                        $tags = array('class', 'title', 'alt');
                        $dont_escape = array();

                        foreach($tags as $tag) {
                            if ($this->Tag->$tag()) {
                                $val = $this->Tag->$tag();
                                if (substr($val, 0, 1)=='{' && substr($val, -1)=='}') {
                                    $attrs[$tag] = '<'.$this->Tag->tag_name().' id="'.str_replace(array('{','}'), '', $val).'" escape="true" />';
                                    $dont_escape[] = $tag;
                                }else{
                                    $attrs[$tag] = PerchUtil::html($val, true);
                                }
                            }
                        }

                        $this->processed_output_is_markup = true;


                        return PerchXMLTag::create('img', 'single', $attrs, $dont_escape);

                        break;
                }
            }

            return $this->_get_image_src($orig_item, $item);
            
        }

        if ($this->Tag->width() || $this->Tag->height()) {
            $PerchImage = new PerchImage;
            return $PerchImage->get_resized_filename($raw, $this->Tag->width(), $this->Tag->height());
        }



        return PERCH_RESPATH.'/'.str_replace(PERCH_RESPATH.'/', '', $raw);
    }
    
    public function get_search_text($raw=false)
    {
        return '';
    }

    public function render_admin_listing($details=false)
    {
        $s = '';

        if (is_array($details)) {

            if ($this->Tag->output()) {
                return $this->get_processed($details);
            }

            $Perch = Perch::fetch();
            $bucket = $Perch->get_resource_bucket($this->Tag->bucket());

            $PerchImage = new PerchImage;          
            
            $json = $details;

            $bucket = $Perch->get_resource_bucket($json['bucket']);

            if (isset($json['sizes']['thumb'])) {
                $image_src  = $json['sizes']['thumb']['path'];
                $image_w    = $json['sizes']['thumb']['w'];
                $image_h    = $json['sizes']['thumb']['h'];
            }
            
            $image_path = PerchUtil::file_path($bucket['file_path'].'/'.$image_src);

            if (file_exists($image_path)) {
                $s .= '<img src="'.PerchUtil::html($bucket['web_path'].'/'.$image_src).'" width="'.($image_w/2).'" height="'.($image_h/2).'" alt="Preview" />';
            }
        }
            
        return $s;
    }

    private function _get_image_src($orig_item, $item)
    {
        if (!isset($item['path'])) return false;
        
        $Perch = Perch::fetch();

        if (isset($orig_item['bucket'])) {
            $bucket = $Perch->get_resource_bucket($orig_item['bucket']);
        }else{
            $bucket = $Perch->get_resource_bucket($this->Tag->bucket());
        }                      
        
        return $bucket['web_path'].'/'.str_replace($bucket['web_path'].'/', '', $item['path']);
    }

    private function _get_image_file($orig_item, $item)
    {
        $Perch = Perch::fetch();

        if (isset($orig_item['bucket'])) {
            $bucket = $Perch->get_resource_bucket($orig_item['bucket']);
        }else{
            $bucket = $Perch->get_resource_bucket($this->Tag->bucket());
        }                      
        
        return PerchUtil::file_path($bucket['file_path'].'/'.str_replace($bucket['file_path'].'/', '', $item['path']));
    }
}


/* ------------ FILE -- note, extends Image ------------ */

class PerchFieldType_file extends PerchFieldType_image
{
    public function render_inputs($details=array())
    {
        $Perch = Perch::fetch();
        $bucket = $Perch->get_resource_bucket($this->Tag->bucket());

        if (!class_exists('PerchAssets_Assets', false)) {
            include_once(PERCH_CORE.'/apps/assets/PerchAssets_Assets.class.php');
            include_once(PERCH_CORE.'/apps/assets/PerchAssets_Asset.class.php');
        }

        $s = $this->Form->image($this->Tag->input_id());
        $s .= $this->Form->hidden($this->Tag->input_id().'_field', '1');

        $assetID     = false;
        $asset_field = $this->Tag->input_id().'_assetID';

        if (isset($details[$this->Tag->input_id()]['assetID'])) {
            $assetID = $details[$this->Tag->input_id()]['assetID'];
        }

        $s .= $this->Form->hidden($asset_field, '');    

        PerchUtil::initialise_resource_bucket($bucket);

        if (!is_writable($bucket['file_path'])) {
            $s .= $this->Form->hint(PerchLang::get('Your resources folder is not writable. Make this folder (') . PerchUtil::html($bucket['web_path']) . PerchLang::get(') writable to upload files.'), 'error');
        }  
 
        if (isset($details[$this->Tag->input_id()]) && $details[$this->Tag->input_id()]!='') {
            $json = $details[$this->Tag->input_id()];

            //PerchUtil::debug($json);

            if (isset($json['bucket'])) {
                $bucket = $Perch->get_resource_bucket($json['bucket']);
            }  

            if (is_array($json) && isset($json['path'])) {
                $path = $json['path'];
            }else{
                if (!is_array($json)) {
                    $path = $json;
                }else{
                    $path = '--false--';
                }
            }

            $file_path = PerchUtil::file_path($bucket['file_path'].'/'.$path);

            if (!file_exists($file_path) && $assetID) {
                $Assets    = new PerchAssets_Assets;
                $Asset     = $Assets->find($assetID);
                if (is_object($Asset)) { 
                    $file_path = $Asset->file_path();
                }
            }else{
                $Asset = false;
            }


            if (isset($json['sizes']['thumb'])) {
                $image_src  = $json['sizes']['thumb']['path'];
                $image_w    = $json['sizes']['thumb']['w'];
                $image_h    = $json['sizes']['thumb']['h'];
                $image_path = PerchUtil::file_path($bucket['file_path'].'/'.$image_src);
                $thumb = true;
            }else{
                $thumb = false;
            }


            if (file_exists($file_path)) {

                $s .= '<div class="asset-badge" data-for="'.$asset_field.'">';

                $type = PerchAssets_Asset::get_type_from_filename($path);

                $s .= '<div class="asset-badge-thumb asset-icon icon asset-'.$type.'">';
                if ($thumb) {
                    $s .= '<img src="'.PerchUtil::html($bucket['web_path'].'/'.$image_src).'" width="'.$image_w.'" height="'.$image_h.'" alt="Preview" />';
                }
                
                $s .= '</div><div class="asset-badge-meta">';

                if (!$this->Tag->is_set('app_mode')) {

                    $s .= '<div class="asset-badge-remove">';
                    $s .= $this->Form->label($this->Tag->input_id().'_remove', PerchLang::get('Remove'), 'inline'). ' ';
                    $s .= $this->Form->checkbox($this->Tag->input_id().'_remove', '1', 0);
                    $s .= '</div>';

                }

                    if ($json) {
                        $s .= '<ul class="meta">';
                        $s .= '<li class="title">';
                        if ($Asset) {
                            $s .= '<a href="'.$Asset->web_path().'">';
                        }
                        $s .= (isset($json['title']) ? $json['title'] : str_replace(PERCH_RESPATH.'/', '', $path));
                        if ($Asset) {
                            $s .= '</a>';
                        }

                        $s .= '</li>';
                        if (isset($json['mime'])) $s .= '<li>'.ucfirst(str_replace('/', ' / ', $json['mime'])).'</li>';
                        
                        
                        $size     = floatval($json['size']);

                        if ($size < 1048576) {
                            $size = round($size/1024, 0).'KB';
                        }else{
                            $size = round($size/1024/1024, 0).'MB';
                        }
                        $s .= '<li>'.$size.'</li>';

                        $s .= '</ul>';
                    }
                  
                $s .= '</div>';
                $s .= '</div>';
                
                
                }
                
            }else{
                $s .= '<div class="asset-badge hidden" data-for="'.$asset_field.'">';
                $s .= '</div>';
            }

            if (isset($file_path) && file_exists($file_path)) $s .= $this->Form->hidden($this->Tag->input_id().'_populated', '1');

            $type = 'doc';
            if ($this->Tag->file_type()) $type = $this->Tag->file_type();
            
            $s .= ' <span class="ft-choose-asset ft-file '.($this->Tag->disable_asset_panel() ? ' assets-disabled' : '').'" data-type="'.$type.'" data-field="'.$asset_field.'" data-bucket="'.PerchUtil::html($bucket['name'], true).'" data-input="'.$this->Tag->input_id().'"></span>';
        
        
        return $s;
    }

    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        if (is_array($raw)) {
            if (isset($raw['_default'])) {
                $raw = $raw['_default'];
            }else{
                $raw = ' ';
            }
        }

        return str_replace(array('/', '\\', '-', '_', '.'), ' ', $raw);
    }
}


/* ------------ MAP ------------ */

class PerchFieldType_map extends PerchFieldType
{
    public static $mapcount = 1;
	public $processed_output_is_markup = true;
    
    public function add_page_resources()
    {
        $Perch = Perch::fetch();
        $Perch->add_foot_content('<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>');
        $Perch->add_javascript(PERCH_LOGINPATH.'/core/assets/js/maps.js');
    }
    
    public function render_inputs($details=array())
    {
        $s = $this->Form->text($this->Tag->input_id().'_adr', $this->Form->get((isset($details[$this->Tag->input_id()])? $details[$this->Tag->input_id()] : array()), 'adr', $this->Tag->default()), 'map_adr');                            
        $s .= '<div class="map" data-btn-label="'.PerchLang::get('Find').'" data-mapid="'.PerchUtil::html($this->Tag->input_id()).'" data-width="'.($this->Tag->width() ? $this->Tag->width() : '460').'" data-height="'.($this->Tag->height() ? $this->Tag->height() : '320').'">';
            if (isset($details[$this->Tag->input_id()]['admin_html'])) {
                $s .= $details[$this->Tag->input_id()]['admin_html'];
                $s .= $this->Form->hidden($this->Tag->input_id().'_lat',  $details[$this->Tag->input_id()]['lat']);
                $s .= $this->Form->hidden($this->Tag->input_id().'_lng',  $details[$this->Tag->input_id()]['lng']);
                $s .= $this->Form->hidden($this->Tag->input_id().'_clat', $details[$this->Tag->input_id()]['clat']);
                $s .= $this->Form->hidden($this->Tag->input_id().'_clng', $details[$this->Tag->input_id()]['clng']);
                $s .= $this->Form->hidden($this->Tag->input_id().'_type', $details[$this->Tag->input_id()]['type']);
                $s .= $this->Form->hidden($this->Tag->input_id().'_zoom', $details[$this->Tag->input_id()]['zoom']);
            }
        $s .= '</div>';
        return $s;
    }
    
    public function get_raw($post=false, $Item=false)
    {
        $var = '';
        if (isset($post[$this->Tag->id().'_adr']) && $post[$this->Tag->id().'_adr']!='') {
            $tmp = array();
            $tmp['adr'] = stripslashes(trim($post[$this->Tag->id().'_adr']));
        
            $map_fields = array('lat', 'lng', 'clat', 'clng', 'type', 'zoom');
            foreach($map_fields as $map_field) {
                if (isset($post[$this->Tag->id().'_'.$map_field]) && $post[$this->Tag->id().'_'.$map_field]!=''){
                    $tmp[$map_field] = $post[$this->Tag->id().'_'.$map_field];
                }
            }
                                                
            $var = $this->_process_map($this->unique_id.'-'.self::$mapcount, $this->Tag, $tmp);
            self::$mapcount++;
        }
        
        return $var;
    }
    
    public function get_processed($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        return $raw['html'];
    }
    
    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
		if (!PerchUtil::count($raw)) return false;

        return $raw['_title'];
    }
        
    private function _process_map($id, $tag, $value)
    {
        $out = array();

        
        if (isset($value['adr'])) {
            
            $out['adr']     = $value['adr'];
            $out['_title']  = $value['adr'];
            $out['_default']= $value['adr'];
            
            if (!isset($value['lat'])) {
            
                $lat = false;
                $lng = false;
            
                $path = '/maps/api/geocode/json?address='.urlencode($value['adr']).'&sensor=false';
                $result = PerchUtil::http_get_request('http://', 'maps.googleapis.com', $path);
                if ($result) {
                    $result = PerchUtil::json_safe_decode($result, true);
                    //PerchUtil::debug($result);
                    if ($result['status']=='OK') {
                        if (isset($result['results'][0]['geometry']['location']['lat'])) {
                            $lat = $result['results'][0]['geometry']['location']['lat'];
                            $lng = $result['results'][0]['geometry']['location']['lng'];
                        }
                    }  
                }
            }else{
                $lat = $value['lat'];
                $lng = $value['lng'];
            }
            
            $out['lat'] = $lat;
            $out['lng'] = $lng;
            
            
            if (!isset($value['clat'])) {
                $clat = $lat;
                $clng = $lng;
            }else{
                $clat = $value['clat'];
                $clng = $value['clng'];
            }
            
            $out['clat'] = $clat;
            $out['clng'] = $clng;
            
            if (!isset($value['zoom'])) {
                if ($tag->zoom()) {
                    $zoom = $tag->zoom();
                }else{
                    $zoom = 15;
                }
            }else{
                $zoom = $value['zoom'];
            }
            
            if (!isset($value['type'])) {
                if ($tag->type()) {
                    $type = $tag->type();
                }else{
                    $type = 'roadmap';
                }
            }else{
                $type = $value['type'];
            }
            
                       
            $adr    = $value['adr'];

            if (PERCH_RWD) {
                $width  = ($tag->width() ? $tag->width() : '');
                $height = ($tag->height() ? $tag->height() : '');  
            }else{
                $width  = ($tag->width() ? $tag->width() : '460');
                $height = ($tag->height() ? $tag->height() : '320'); 
            }
            
            $static_width  = ($width  == '' ? '460' : $width);
            $static_height = ($height == '' ? '320' : $height);

            $out['zoom'] = $zoom;
            $out['type'] = $type;
                        
            $r  = '<img id="cmsmap'.PerchUtil::html($id).'" src="//maps.google.com/maps/api/staticmap';
            $r  .= '?center='.$clat.','.$clng.'&amp;sensor=false&amp;size='.$static_width.'x'.$static_height.'&amp;zoom='.$zoom.'&amp;maptype='.$type;
            if ($lat && $lng)   $r .= '&amp;markers=color:red|color:red|'.$lat.','.$lng;    
            $r  .= '" ';
            if ($tag->class())  $r .= ' class="'.PerchUtil::html($tag->class()).'"';
            $r  .= ' width="'.$static_width.'" height="'.$static_height.'" alt="'.PerchUtil::html($adr).'" />';
            
            $out['admin_html'] = $r;

            $map_js_path = PerchUtil::html(PERCH_LOGINPATH).'/core/assets/js/public_maps.min.js';
            if (defined('PERCH_MAP_JS') && PERCH_MAP_JS) {
                $map_js_path = PerchUtil::html(PERCH_MAP_JS);
            }
            
            // JavaScript
            $r .= '<script type="text/javascript">/* <![CDATA[ */ ';
            $r .= "if(typeof CMSMap =='undefined'){var CMSMap={};CMSMap.maps=[];document.write('<scr'+'ipt type=\"text\/javascript\" src=\"".$map_js_path."\"><'+'\/sc'+'ript>');}";
            $r .= "CMSMap.maps.push({'mapid':'cmsmap".PerchUtil::html($id)."','width':'".$width."','height':'".$height."','type':'".$type."','zoom':'".$zoom."','adr':'".addslashes(PerchUtil::html($adr))."','lat':'".$lat."','lng':'".$lng."','clat':'".$clat."','clng':'".$clng."'});";
            $r .= '/* ]]> */';
            $r .= '</script>';

            
            if (defined('PERCH_XHTML_MARKUP') && PERCH_XHTML_MARKUP==false) {
    		    $r = str_replace('/>', '>', $r);
    		}
            
            $out['html'] = $r;
        }
        
        return $out;
    }
}


/* ---- DATA SELECT ---- */

class PerchFieldType_dataselect extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        $Perch = Perch::fetch();

        $page = false;

        // Find the path path.
        // 
        // Has it been set as an attribute?
        if ($this->Tag->page()) {
            $page = $this->Tag->page();
        }

        // Has the PageID been set from the edit page?
        if (!$page && $this->Tag->page_id()) {
            $Pages = new PerchContent_Pages;
            $Page = $Pages->find($this->Tag->page_id());
            if ($Page) {
                $page = $Page->pagePath();
            }
        }

        // Use the current page.
        if (!$page) {
            $page = $Perch->get_page();
        }

        $region = $this->Tag->region();
        $field_id = $this->Tag->options();
        $values_id = $this->Tag->values();

        if (!class_exists('PerchContent_Regions', false)) {
            include_once(PERCH_CORE.'/apps/content/PerchContent_Regions.class.php');
            include_once(PERCH_CORE.'/apps/content/PerchContent_Items.class.php');
            include_once(PERCH_CORE.'/apps/content/PerchContent_Item.class.php');
        }

        $Regions = new PerchContent_Regions;

        $opts = $Regions->find_data_select_options($page, $region, $field_id, $values_id);



        if (PerchUtil::bool_val($this->Tag->allowempty())== true) {
            array_unshift($opts, array('label'=>'', 'value'=>''));
        }
        
        return $this->Form->select($this->Tag->input_id(), $opts, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()));
    }
}

/* ------------ COMPOSITE ------------ */

class PerchFieldType_composite extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return '';
    }
    
    public function get_raw($post=false, $Item=false)
    {
        
        $fields = explode(' ', $this->Tag->for());
        if (PerchUtil::count($fields)) {

            $out = array();
            foreach($fields as $field) {
                $field = trim($field);
                if (isset($post[$field]) && $post[$field]!='') {
                    $out[] = trim(stripslashes($post[$field]));
                }
            }
            //PerchUtil::debug($_POST);
            $join = ' ';
            if ($this->Tag->join()) {
                $join = $this->Tag->join();
            }
            return implode($join, $out);

        }
    
        
        return '';
    }
}

/* ------------ REPEATER ------------ */

class PerchFieldType_repeater extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return '';
    }
    
    public function get_raw($post=false, $Item=false)
    {
        return '';
    }

    public function get_processed($raw=false)
    {    
        if (is_array($raw)) {
           
            if ($this->Tag->output()) {
                switch($this->Tag->output()) {        
                    case 'count':
                        return count($raw); 
                        break;
                }
            }
        }

        return '';
    }
}

/* ------------ SMARTTEXT ------------ */

class PerchFieldType_smarttext extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        if (isset($details[$this->Tag->input_id()]) && $details[$this->Tag->input_id()]!='') {
            $data = $details[$this->Tag->input_id()];        
            if (is_array($data)) {
                $details = array($this->Tag->id()=>$data['raw']);
            }   
        }

        $s = '';
        $id = $this->Tag->id();
        $s = $this->Form->text($this->Tag->input_id(), $this->Form->get($details, $id, $this->Tag->default(), $this->Tag->post_prefix()), $this->Tag->size(), $this->Tag->maxlength());
                
        return $s;
    }

    public function get_raw($post=false, $Item=false)
    {
        if ($post===false) {
            $post = $_POST;
        }
        
        $id = $this->Tag->id();
        if (isset($post[$id])) {
            $raw = trim($post[$id]);
            
            $value = stripslashes($raw);

            // Strip HTML by default
            if (!is_array($value) && PerchUtil::bool_val($this->Tag->html()) == false) {
                $value = PerchUtil::html($value);
                $value = strip_tags($value);
            }

            if (!class_exists('\\Michelf\\SmartyPants', false) && class_exists('SmartyPants', true)) { 
                // sneaky autoloading hack 
            }

            $SmartyPants = new \Michelf\SmartyPants;

            $value = $SmartyPants->transform($value);
            if (PERCH_HTML_ENTITIES==false) {
                $value = html_entity_decode($value, ENT_NOQUOTES, 'UTF-8');    
            }
            
            $store = array(
                'raw' => $raw,
                'processed' => $value
            );
            
            $this->raw_item = $store;
        
            return $this->raw_item;
        }
        
        return null;
    }
  
    public function get_processed($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        $value = $raw;
        
        if (is_array($value)) {
            if (isset($value['processed'])) {
                $this->processed_output_is_markup = true;
                return $value['processed'];
            }
            
            if (isset($value['raw'])) {
                return $value['raw'];
            }
        }

        return $value;
    }
       
    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        if (is_array($raw)) {
            
            if (isset($raw['processed'])) {
                return strip_tags($raw['processed']);
            }
                    
            if (isset($raw['raw'])) {
                return $raw['raw'];
            }
            
        }
        
        return $raw;
    }
}

/* ------------ CATEGORY ------------ */

class PerchFieldType_category extends PerchFieldType
{
    public function add_class_dependancies()
    {
        if (!class_exists('PerchCategories_Categories', false)) {
            include_once(PERCH_CORE.'/apps/categories/PerchCategories_Categories.class.php');
            include_once(PERCH_CORE.'/apps/categories/PerchCategories_Category.class.php');
        }
    }

    public function add_page_resources()
    {
        $Perch = Perch::fetch();
        
        $Perch->add_javascript(PERCH_LOGINPATH.'/core/assets/js/chosen.jquery.min.js');
        $Perch->add_javascript(PERCH_LOGINPATH.'/core/assets/js/categories.js');
        //$Perch->add_css(PERCH_LOGINPATH.'/core/assets/css/chosen.min.css');
    }

    public function render_inputs($details=array())
    {
        $mode = 'select';

        if ($this->Tag->display_as() && $this->Tag->display_as()=='checkboxes') {
            $mode = 'checkboxes';
        }

        $setSlug = $this->Tag->set();
        if (!$setSlug) $setSlug = 'default';
        $Categories = new PerchCategories_Categories();
        $cats = $Categories->get_for_set($setSlug);

        $opts = array();
        if (PerchUtil::count($cats)) {
            foreach($cats as $Category) {
                $opts[] = array('label'=>$Category->catDisplayPath(), 'value'=>$Category->id());
            }
        }           

        switch ($mode) {
            case 'checkboxes':
                return $this->render_checkboxes($details, $opts);
                break;
            default: 
                return $this->render_select($details, $opts);
                break;
        }

        
    }

    private function render_select($details, $opts)
    {
        return $this->Form->select($this->Tag->input_id(), $opts, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), 'categories', true);
    }

    private function render_checkboxes($details, $opts)
    {
        $multicol = 'fieldtype';
        if (PerchUtil::count($opts) > 4) $multicol .= ' multi-col';

        return $this->Form->checkbox_set($this->Tag->input_id(), false, $opts, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $multicol);
        
    }

    public function get_raw($post=false, $Item=false)
    {
        if ($post===false) {
            $post = $_POST;
        }
        
        $id = $this->Tag->id();
        if (isset($post[$id])) {

            $this->raw_item = $post[$id];
            return $this->raw_item;
        }
        
        return null;
    }

    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        if (is_array($raw) && count($raw)) {
            $out = array();
            $Categories = new PerchCategories_Categories();
            foreach($raw as $catID) {
                $Cat = $Categories->find($catID);
                $out[] = $Cat->catTitle();
            }

            return implode(', ', $out);
        }

        return $raw;
    }

    public function render_admin_listing($raw=false)
    {
        return PerchUtil::html($this->get_search_text($raw));
    }

    public function get_index($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();
        
        $id = $this->Tag->id();

        $out = array();

        if (is_array($raw)) {

            $Categories = new PerchCategories_Categories();

            foreach($raw as $key=>$val) {
                if (!is_array($val)) {
                    $Cat = $Categories->find($val);
                    if (is_object($Cat)) $out[] = array('key'=>'_category', 'value'=>$Cat->catPath());
                }
            }
            
        }


        return $out;
    }

}