<?php

class PerchFieldTypes
{
    private static $_seen = array();

    public static function get($type, $Form, $Tag, $all_tags=array(), $app_id='content')
    {
        $r = false;

        if (!$type) {
            $tag_name = $Tag->tag_name();

            switch($tag_name) {
                case 'perch:categories':
                    $type = 'category';
                    break;

                case 'perch:related':
                    $type = 'related';
                    break;

                default:
                    $type = 'text';
            }
        }

        if (!$Form) {
            $Form = null;
        }

        if (!$Tag) {
            $Tag = null;
        }

        $classname = 'PerchFieldType_'.$type;

        if (class_exists($classname, false)){
            $r = new $classname($Form, $Tag, $app_id);
            if (!in_array($classname, self::$_seen)) {
                $Perch = Perch::fetch();
                if ($Perch->admin) {
                    if (PerchUtil::count($all_tags)) $r->set_sibling_tags($all_tags);
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
                    if (PerchUtil::count($all_tags)) $r->set_sibling_tags($all_tags);
                    $r->add_page_resources();
                }

                self::$_seen[] = $classname;
            }
        }

        if (!is_object($r)) {
            $r = new PerchFieldType($Form, $Tag, $app_id);
        }

        if (PerchUtil::count($all_tags)) {
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

/* ------------ EDIT CONTROL (INTERNAL) ------------ */

class PerchFieldType_editcontrol extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        return $this->Form->hidden($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), false, $this->Tag->class());
    }

    public function get_search_text($raw=false)
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
    public $input_type = 'color';

    public function render_inputs($details=array())
    {
        $s = '';
        $id = $this->Tag->id();
        $attrs = array();

        $size = 'm';

        if ($this->Tag->size()) {
            $size = $this->Tag->size();
        }

        $copy_atts = ['placeholder', 'autocomplete', 'autofill'];

        foreach($copy_atts as $att) {
            if ($this->Tag->is_set($att)) {
                $attrs[] = $att.'="' .PerchUtil::html($this->Tag->$att(), true). '"';
            }
        }

        $attrs = array_merge($attrs, $this->get_annotation_attributes());

        $attrs = implode(' ', $attrs);

        $s = $this->Form->text($this->Tag->input_id(), 
                            $this->Form->get($details, $id, $this->Tag->default(), $this->Tag->post_prefix()), 
                            $size, 
                            $this->Tag->maxlength(), 
                            $this->input_type, 
                            $attrs.$this->Tag->get_data_attribute_string()
                        );

        $s .= $this->render_field_annotations();

        return $s;
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

/* ------------ PASSWORD ------------ */

class PerchFieldType_password extends PerchFieldType
{
    public $input_type = 'password';
}

/* ------------ NUMBER ------------ */

class PerchFieldType_number extends PerchFieldType
{

    public function render_inputs($details=array())
    {
        $attributes = '';
        $attrs = array();
        $search = array('min', 'max', 'step');

        foreach($search as $s) {
            if ($this->Tag->is_set($s)) $attrs[] = $s.'='.$this->Tag->$s;
        }

        $attributes = implode(' ', $attrs);


        return $this->Form->text($this->Tag->input_id(),
                                $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()),
                                'input-simple '.$this->Tag->size(),
                                $this->Tag->maxlength(),
                                'number',
                                $attributes);
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

    public function import_data($data)
    {
        $id = $this->Tag->id();
        if (array_key_exists($id, $data)) {
            if (!$this->Tag->native()) {
                $time = strtotime($data[$id]);
                $data[$id.'_year']   = date('Y', $time);
                $data[$id.'_month']  = date('m', $time);
                $data[$id.'_day']    = date('d', $time);
                $data[$id.'_hour']   = date('H', $time);
                $data[$id.'_minute'] = date('i', $time);

            }

            return $this->get_raw($data);
        }

        return null;
    }

    public function get_raw($post=false, $Item=false)
    {
        $id = $this->Tag->id();

        if ($this->Tag->native()) {
            if ($post===false) {
                $post = PerchRequest::post();
            }

            if (isset($post[$id])) {
                $raw = trim(PerchUtil::safe_stripslashes($post[$id]));
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

    public function get_content_summary($details=array(), $Template)
    {
        if (!PerchUtil::count($details)) return '';

        $value = parent::get_content_summary($details, $Template);
        $value = $Template->format_value($this->Tag, $value);
        
        return PerchUtil::html($value, true);
    }
}


/* ------------ TIME ------------ */

class PerchFieldType_time extends PerchFieldType
{
    public function import_data($data)
    {
        $id = $this->Tag->id();
        if (array_key_exists($id, $data)) {
            if (!$this->Tag->native()) {
                $time = strtotime($data[$id]);
                $data[$id.'_year']   = date('Y', $time);
                $data[$id.'_month']  = date('m', $time);
                $data[$id.'_day']    = date('d', $time);
                $data[$id.'_hour']   = date('H', $time);
                $data[$id.'_minute'] = date('i', $time);

            }

            return $this->get_raw($data);
        }

        return null;
    }

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

    public function get_content_summary($details=array(), $Template)
    {
        if (!PerchUtil::count($details)) return '';

        $value = parent::get_content_summary($details, $Template);
        $value = $Template->format_value($this->Tag, $value);
        
        return PerchUtil::html($value, true);
    }
}


/* ------------ PERIOD ------------ */

class PerchFieldType_period extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        $s = '';

        $s .= $this->Form->text($this->Tag->input_id().'_count',
                                $this->Form->get((isset($details[$this->Tag->input_id()])? $details[$this->Tag->input_id()] : array()), 'count', $this->Tag->default(), $this->Tag->post_prefix()),
                                's',
                                $this->Tag->maxlength(),
                                'number');

        $opts = array(
                array('label' => PerchLang::get('minutes'), 'value' => 'MINUTES'),
                array('label' => PerchLang::get('hours'),   'value' => 'HOURS'),
                array('label' => PerchLang::get('days'),    'value' => 'DAYS'),
                array('label' => PerchLang::get('weeks'),   'value' => 'WEEKS'),
                array('label' => PerchLang::get('months'),  'value' => 'MONTHS'),
                array('label' => PerchLang::get('years'),   'value' => 'YEARS'),
            );

        $s .= ' ' .$this->Form->select($this->Tag->input_id().'_unit',
                                $opts,
                                $this->Form->get((isset($details[$this->Tag->input_id()])? $details[$this->Tag->input_id()] : array()), 'unit', strtoupper($this->Tag->default_unit()), $this->Tag->post_prefix())
                                );

        return $s;
    }

    public function import_data($data)
    {
        $id = $this->Tag->id();
        if (array_key_exists($id, $data)) {
            if (is_array($data[$id]) && array_key_exists('count', $data[$id]) && array_key_exists('unit', $data)) {
                return $this->get_raw($data);    
            } else {
                throw new \Exception("Period data should be in format ['count'=>2, 'unit'=>'WEEKS']");
            }
        }

        return null;
    }

    public function get_raw($post=false, $Item=false)
    {
        $id = $this->Tag->id();

        if ($post===false) {
            $post = PerchRequest::post();
        }

        $this->raw_item['_default'] = null;

        if (isset($post[$id.'_count']) && $post[$id.'_count']!='') {
            $this->raw_item['count'] = $post[$id.'_count'];
            $this->raw_item['_default'] = '+'.$this->raw_item['count'].' ';

            if (isset($post[$id.'_unit'])) {
                $this->raw_item['unit'] = $post[$id.'_unit'];
                $this->raw_item['_default'] .= $this->raw_item['unit'];
            }

        }

        return $this->raw_item;
    }

    public function get_search_text($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        return $raw['_default'];
    }

}

/* ------------ SLUG ------------ */

class PerchFieldType_slug extends PerchFieldType
{
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
                            $tmp[] = str_replace('__', '_', $this->Tag->post_prefix()).$field;
                        }
                    }

                    if (!$value) $attrs .= 'data-slug-for="'.PerchUtil::html(implode(' ',$tmp), true).'"';

                }

            }

            $s = $this->Form->text($this->Tag->input_id(), $value, 'input-simple '.$this->Tag->size('m'), $this->Tag->maxlength(), false, $attrs);

            return $s;
        }

        return '';
    }

    public function get_raw($post=false, $Item=false)
    {

        $value = false;

        if ($post===false) {
            $post = PerchRequest::post();
        }

        $id = $this->Tag->id();
        if (isset($post[$id])) {
            $this->raw_item = trim(PerchUtil::safe_stripslashes($post[$id]));
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
                        $str[] = trim(PerchUtil::safe_stripslashes($post[$part]));
                    }
                }
                return PerchUtil::urlify(implode(' ', $str));
            }

            if (isset($post[$this->Tag->for()])) {
                return PerchUtil::urlify(trim(PerchUtil::safe_stripslashes($post[$this->Tag->for()])));
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
    public $hints_before = true;
    private $native_editors = ['markitup', 'redactor', 'redactor2', 'simplemde'];

    public function add_page_resources()
    {
        $Perch = Perch::fetch();
        $siblings = $this->get_sibling_tags();

        if (PERCH_CUSTOM_EDITOR_CONFIGS) {
            $Perch->add_fe_plugin('user-plugins', '{"js": ["'.PERCH_LOGINPATH.'/addons/plugins/editors/config.js"],"css": []}');
        }

        if (is_array($siblings)) {
            $seen_editors = array();
            foreach($siblings as $tag) {
                if ($tag->editor() && !in_array($tag->editor(), $seen_editors)) {
                    $file = $this->get_editor_path($tag->editor());
                    if (is_file($file)) {
                        $contents = file_get_contents($file);
                        $contents = str_replace('PERCH_LOGINPATH', PERCH_LOGINPATH, $contents);
                        $config = 'default';
                        if ($tag->editor_config()) $config = $tag->editor_config();
                        $contents = str_replace('PERCH_EDITOR_CONFIG', $config, $contents);
                        //$Perch->add_foot_content($contents);
                        $Perch->add_fe_plugin($tag->editor(), $contents);
                        $seen_editors[] = $tag->editor();
                    }else{
                        PerchUtil::debug('Editor requested, but not installed: '.$this->Tag->editor(), 'error');
                    }
                }
            }
        }else{
            if ($this->Tag->editor()) {
                $file = $this->get_editor_path($this->Tag->editor());
                if (is_file($file)) {
                    $Perch->add_fe_plugin($this->Tag->editor(), str_replace('PERCH_LOGINPATH', PERCH_LOGINPATH, file_get_contents($file)));
                }else{
                    PerchUtil::debug('Editor requested, but not installed: '.$this->Tag->editor(), 'error');
                }

            }
        }
    }

    private function get_editor_path($editor)
    {
        if (in_array($editor, $this->native_editors)) {
            $dir  = PerchUtil::file_path(PERCH_PATH.'/core/editors/'.$editor);
        } else {
            $dir  = PerchUtil::file_path(PERCH_PATH.'/addons/plugins/editors/'.$editor);
        }

        $file = PerchUtil::file_path($dir.'/_config.json');
        return $file;
    }

    public function render_inputs($details=array())
    {
        $data_atrs = [];

        $classname = 'input-simple autowidth ';
        if ($this->Tag->editor()) {
            $classname .= $this->Tag->editor();
            $data_atrs['editor'] = $this->Tag->editor();
        }  
        if ($this->Tag->textile())    $classname .= ' textile';
        if ($this->Tag->markdown())   $classname .= ' markdown';
        if ($this->Tag->flang())      $classname .= ' '.PerchUtil::html($this->Tag->flang(), true);
        
        if (!$this->Tag->textile() && !$this->Tag->markdown() && $this->Tag->html()) $classname .= ' html';
        if ($this->Tag->size()) {
            $classname .= ' '.$this->Tag->size();
        } else {
            //$classname .= ' l';
        }

        
        if ($this->Tag->imagewidth())           $data_atrs['width']   = $this->Tag->imagewidth();
        if ($this->Tag->imageheight())          $data_atrs['height']  = $this->Tag->imageheight();
        if ($this->Tag->imagecrop())            $data_atrs['crop']    = $this->Tag->imagecrop();
        if ($this->Tag->imageclasses())         $data_atrs['classes'] = $this->Tag->imageclasses();
        if ($this->Tag->imagequality())         $data_atrs['quality'] = $this->Tag->imagequality();
        if ($this->Tag->is_set('imagesharpen')) $data_atrs['sharpen'] = $this->Tag->imagesharpen();
        if ($this->Tag->imagedensity())         $data_atrs['density'] = $this->Tag->imagedensity();
        if ($this->Tag->bucket())               $data_atrs['bucket']  = $this->Tag->bucket();

        $attrs = [];
        $attrs = array_merge($attrs, $this->get_annotation_attributes());

        if ($this->Tag->chars())                $attrs[] = 'maxlength="'.(int)$this->Tag->chars().'"';
        if ($this->Tag->maxlength())            $attrs[] = 'maxlength="'.(int)$this->Tag->maxlength().'"';

        $attrs = implode(' ', $attrs);

        if ($this->Tag->editor_config())  $data_atrs['editor-config'] = $this->Tag->editor_config();

        if (isset($details[$this->Tag->input_id()]) && $details[$this->Tag->input_id()]!='') {
            $data = $details[$this->Tag->input_id()];
            if (is_array($data)) {
                $details = array($this->Tag->id()=>$data['raw']);
            }
        }

        $data_atrs['source'] = base64_encode($this->Tag->get_original_tag_string());

        $s = $this->Form->textarea($this->Tag->input_id(), $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), $classname, $data_atrs, $attrs);
        //$s .= '<div class="clear"></div>';

        $s .= $this->render_field_annotations();

        return $s;
    }

    public function get_raw($post=false, $Item=false)
    {
        if ($post===false) {
            $post = PerchRequest::post();
        }

        $id = $this->Tag->id();
        if (isset($post[$id])) {
            $raw = trim($post[$id]);

            // Redactor 3 craziness
            if ($raw && $this->Tag->editor()==='redactor' && $raw === '<p><br></p>') {
                $raw = '';
            }
            // End Redactor 3 craziness

            $raw = PerchUtil::safe_stripslashes($raw);
            $value = $raw;
            $flang = 'plain';

            if ($this->Tag->html()) {
                $flang = 'html';
            }

            $formatting_language_used = false;

            // Strip HTML by default
            if (!is_array($value) && PerchUtil::bool_val($this->Tag->html()) == false) {
                $value = PerchUtil::html($value);
                $value = strip_tags($value);
            }

            $value = $this->parse_shortcodes($value);

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
                $flang = 'textile';
            }

            // Markdown
            if (!$formatting_language_used && PerchUtil::bool_val($this->Tag->markdown()) == true) {
                
                $Markdown = new PerchParsedown();
                $value = $Markdown->text($value);

                $formatting_language_used = true;
                $flang = 'markdown';
            }else{

                // Smartypants without Markdown (MD gets it by default)

                if (PerchUtil::bool_val($this->Tag->smartypants()) == true) {

                    $Markdown = new PerchParsedown();
                    $value = $Markdown->smartypants($value);

                    $flang = 'smartypants';
                }

            }



            $store = array(
                '_flang'    => $flang,
                'raw'       => $raw,
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

    public function get_index($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        $id = $this->Tag->id();

        $out = array();

        $out[] = array('key'=>$id, 'value'=>trim($this->get_search_text($raw)));

        return $out;
    }
}

/* ------------ CHECKBOX ------------ */

class PerchFieldType_checkbox extends PerchFieldType
{
    public $wrap_class = 'checkbox-single';

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

    public function render_admin_listing($raw=false)
    {
        $opts_str = $this->Tag->options();
        $opts = explode(',', $opts_str);
        if (PerchUtil::count($opts)) {
            foreach($opts as $opt) {
                $parts = explode('|', $opt);
                if (PerchUtil::count($parts)) {
                    if (isset($parts[1])) {
                        if (trim($parts[1])==$raw) {
                            return trim($parts[0]);
                        }
                    }else{
                        if (trim($parts[0])==$raw) {
                            return trim($parts[0]);
                        }
                    }
                }
            }
        }
        return PerchUtil::html($this->get_processed($raw));
    }

    public function get_content_summary($details=array(), $Template)
    {
        if (!PerchUtil::count($details)) return '';

        $value = parent::get_content_summary($details, $Template);
        
        $opts_str = $this->Tag->options();
        $opts = explode(',', $opts_str);
        if (PerchUtil::count($opts)) {
            foreach($opts as $opt) {
                $parts = explode('|', $opt);
                if (PerchUtil::count($parts)) {
                    if (isset($parts[1])) {
                        if (trim($parts[1])==$value) {
                            return PerchUtil::html(trim($parts[0]), true);
                        }
                    }else{
                        if (trim($parts[0])==$value) {
                            return PerchUtil::html(trim($parts[0]), true);
                        }
                    }
                }
            }
        }

        return PerchUtil::html($value, true);
    }
}


/* ------------ RADIO ------------ */

class PerchFieldType_radio extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        $s = '<div class="radio-group">';
        $options = explode(',', $this->Tag->options());
        if (PerchUtil::count($options) > 0) {
            $k = 0;
            $attributes = $this->Tag->get_data_attribute_string();
            foreach($options as $option) {
                $val    = trim($option);
                $label  = $val;
                if (strpos($val, '|')!==false) {
                    $parts = explode('|', $val);
                    $label = $parts[0];
                    $val   = $parts[1];
                }
                $id  = $this->Tag->input_id() . $k;
                $s .= '<div class="field-wrap radio-pair">';
                $s .= $this->Form->radio($id, $this->Tag->input_id(), $val, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), '', $attributes);
                $this->Form->disable_html_encoding();
                $s .= $this->Form->label($id, $label, 'label-radio', false, false);
                $this->Form->enable_html_encoding();
                $s .= '</div>';
                $k++;
            }
        }
        $s .='</div>';

        return $s;
    }
}


/* ------------ IMAGE ------------ */

class PerchFieldType_image extends PerchFieldType
{
    public static $file_paths = array();

    public $wrap_class = 'annotated ';

    protected $accept_types = 'webimage';


    public function render_inputs($details=array())
    {
        $Perch = Perch::fetch();
        $Assets = new PerchAssets_Assets;


        if ($this->Tag->bucket()) {
            $Users       = new PerchUsers;
            $CurrentUser = $Users->get_current_user();
            $buckets = explode(' ', $this->Tag->bucket());
            $buckets = $Assets->hydrate_bucket_list($buckets, $CurrentUser);
            if (count($buckets)) {
                $bucket  = $buckets[0];
                $Bucket = PerchResourceBuckets::get($bucket);    
            } else {
                $Bucket = PerchResourceBuckets::get('default');
            }
            
        } else {
            $Bucket = PerchResourceBuckets::get($this->Tag->bucket());
        }
        



        

        $PerchImage = new PerchImage;
        $s = $this->Form->image($this->Tag->input_id(), $this->Tag->title());
        $s .= $this->Form->hidden($this->Tag->input_id().'_field', '1');

        $assetID     = false;
        $Asset       = false;
        $asset_field = $this->Tag->input_id().'_assetID';

        $badge_rendered = false;

        if (isset($details[$this->Tag->input_id()]['assetID'])) {
            $assetID = $details[$this->Tag->input_id()]['assetID'];
        }

        if ($assetID) {
            $Asset = $Assets->find($assetID);       
        }

        $s .= $this->Form->hidden($asset_field, $assetID);

        $Bucket->initialise();

        if (!$Bucket->ready_to_write()) {
            $s .= $this->Form->hint(PerchLang::get('Your resources folder is not writable. Make this folder (') . PerchUtil::html($Bucket->get_web_path()) . PerchLang::get(') writable to upload images.'), 'error');
        }

        $type = 'img';
        if ($this->Tag->file_type()) $type = $this->Tag->file_type();

        
        $Settings = PerchSettings::fetch();
        $permissive = '';
        if (!(int)$Settings->get('assets_restrict_buckets')->val()) {
            $permissive = ' data-permissive-bucketing="true"';
        } 


        $add_cta = ' <div class="asset-add ft-choose-asset'.($this->Tag->disable_asset_panel() ? ' assets-disabled' : '').'" data-type="'.$type.'" data-field="'.$asset_field.'" data-input="'.$this->Tag->input_id().'" data-app="'.$this->app_id.'" data-app-uid="'.$this->unique_id.'" data-bucket="'.PerchUtil::html($Bucket->get_name(), true).'"'.$permissive.'></div>';


        if (isset($details[$this->Tag->input_id()]) && $details[$this->Tag->input_id()]!='') {
            $json = $details[$this->Tag->input_id()];

            //PerchUtil::debug($json);

            if (isset($json['bucket'])) {
                $Bucket = PerchResourceBuckets::get($json['bucket']);
            }

            if (isset($json['mime']) && strpos($json['mime'],'svg')!==false) {
                if ($Asset) {
                    $json = array_merge($json, $Asset->get_fieldtype_profile());
                    //PerchUtil::debug($json);
                }
            }

            if (isset($json['sizes']['thumb'])) {

                $image_src  = $json['sizes']['thumb']['path'];
                $image_w    = $json['sizes']['thumb']['w'];
                $image_h    = $json['sizes']['thumb']['h'];

            } else {

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

            if ($image_src) {
                
                $image_path = PerchUtil::file_path($Bucket->get_file_path().'/'.$image_src);

                $s .= '<div class="asset-badge" data-for="'.$asset_field.'">
                        <div class="asset-badge-inner">';

                $variant_key = 'w'.$this->Tag->width().'h'.$this->Tag->height().'c'.($this->Tag->crop() ? '1' : '0').($this->Tag->density() ? '@'.$this->Tag->density().'x': '');

                $variant = (isset($json['sizes'][$variant_key]) ? $json['sizes'][$variant_key] : false);

                if (!$variant) {
                    $variant = $json;
                }


                $s .= '<div class="asset-badge-thumb">';
                    $s .= '<img src="'.PerchUtil::html($Bucket->get_web_path().'/'.$image_src).'" width="'.$image_w.'" height="'.$image_h.'" alt="Preview">';

                    // Remove
                    $s .= '<div class="asset-badge-remove">';
                    $s .= '<span class="asset-ban hidden">'.PerchUI::icon('assets/ban-alt', 64).'</span>';
                    $s .= '<span class="asset-badge-remove-fields">';
                    $s .= $this->Form->label($this->Tag->input_id().'_remove', PerchLang::get('Remove'), 'inline'). ' ';
                    $s .= $this->Form->checkbox($this->Tag->input_id().'_remove', '1', 0);
                    $s .= '</span><a href="#" class="asset-badge-remove-action" data-checkbox="'.$this->Tag->input_id().'_remove'.'">'.PerchUI::icon('core/cancel', 24, PerchLang::get('Remove')).'</a>';
                    $s .= '</div>';
                    // End remove

                $s .= '</div>'; // .asset-badge-thumb

                $s .= '<div class="asset-badge-meta">';

                if (!$this->Tag->is_set('app_mode')) {

                    if ($variant) {
                        //PerchUtil::debug($variant, 'notice');

                        $s .= '<h3 class="title">';
                        if ($Asset) {
                            $s .= PerchUtil::html($Asset->title());
                        } else {
                            $s .= PerchUtil::html((isset($json['title']) ? $json['title'] : $variant['path']));
                        }
                        $s .= '</h3>';

                        $s .= '<ul class="meta">';                        
                    
                        if ($Asset) {
                            $s .= '<li>'.PerchUI::icon('assets/o-photo', 12).' ';
                            $s .= $Asset->display_mime().'</li>';
                        } else {
                            if (isset($variant['mime']) && $variant['mime']!='') {
                                $s .= '<li>'.PerchUI::icon('assets/o-photo', 12).' ';
                                $s .= ucfirst(str_replace('/', ' / ', $variant['mime'])).'</li>';
                            }    
                        }

                        
                        

                        $size     = floatval($variant['size']);
                        if ($size < 1048576) {
                            $size = round($size/1024, 0).'<span class="unit">KB</span>';
                        }else{
                            $size = round($size/1024/1024, 0).'<span class="unit">MB</span>';
                        }
                        if (isset($variant['w']) && isset($variant['h'])) {
                            $s .= '<li>'.PerchUI::icon('assets/o-crop', 12).' ';
                            $s .= ''.$variant['w'].' x '.$variant['h'].'<span class="unit">px</span> @ ';    
                        } else {
                            $s .= '<li>'.PerchUI::icon('assets/o-weight-scale', 12).' ';
                        }
                        $s .= $size.'</li>';

                        $s .= '</ul>';

                        $s .= $add_cta;
                    } else {
                        PerchUtil::debug('no variant');
                    }

                } else {

                    if (!$Asset) $Asset = $Assets->find($assetID);

                    if ($Asset) {
                        $s .= '<ul class="meta">';
                        $s .= '<li><a href="'.$Asset->web_path().'">'.PerchLang::get('Download original file').'</a></li>';
                        $s .= '</ul>';
                    }

                } // app_mode

                $s .= '</div>';
                $s .= '</div>';
                $s .= '</div>';

                $badge_rendered = true;

            }

        }

        if (!$badge_rendered) {
            $s .= '<div class="asset-badge" data-for="'.$asset_field.'">
                        <div class="asset-badge-inner">
                            <div class="asset-badge-thumb thumbless">';
                        $s .= PerchUI::icon('assets/upload', 64, PerchLang::get('Upload'));
                    $s .= '</div>';
                    $s .= '<div class="asset-badge-meta">';
                        $s .= $add_cta;
                    $s .= '</div>';
                $s .= '</div>';
            $s .= '</div>';
        }


        if (isset($image_path) && !empty($image_path)) $s .= $this->Form->hidden($this->Tag->input_id().'_populated', '1');

        
        return $s;
    }

    public function get_raw($post=false, $Item=false)
    {
        $store                 = array();
        
        $Perch                 = Perch::fetch();
        $Bucket                = PerchResourceBuckets::get($this->Tag->bucket());
        
        $Bucket->initialise();

        $image_folder_writable = $Bucket->ready_to_write();
        
        $item_id               = $this->Tag->input_id();
        $asset_reference_used  = false;
        
        $target                = false;
        $filesize              = false;
        
        $Assets                = new PerchAssets_Assets;
        $AssetMeta             = false;

        // Asset ID?
        if (isset($post[$this->Tag->id().'_assetID']) && $post[$this->Tag->id().'_assetID']!='') {
            $new_assetID = $post[$this->Tag->id().'_assetID'];

            $Asset = $Assets->find($new_assetID);

            if (is_object($Asset)) {
                $target   = $Asset->file_path();
                $filename = $Asset->resourceFile();

                $store['assetID']  = $Asset->id();
                $store['title']    = $Asset->resourceTitle();
                $store['_default'] = $Asset->web_path();
                $store['bucket']   = $Asset->resourceBucket();

                if ($store['bucket']!=$Bucket->get_name()) {
                    $Bucket = PerchResourceBuckets::get($store['bucket']);
                }

                $asset_reference_used = true;
            }
        }

        if ($image_folder_writable && isset($_FILES[$item_id]) && (int) $_FILES[$item_id]['size'] > 0) {

            // If we haven't already got this file
            if (!isset(self::$file_paths[$this->Tag->id()])) {

                // Verify the file type / size / name
                if ($this->_file_is_acceptable($_FILES[$item_id])) {

                    // We do this before writing to the bucket, as it performs better for remote buckets.
                    $AssetMeta = $Assets->get_meta_data($_FILES[$item_id]['tmp_name'], $_FILES[$item_id]['name']);

                    // If it's an image, fix the orientation if we can
                    if ($this->Tag->type()=='image') {
                        $PerchImage = new PerchImage;
                        $PerchImage->orientate_image($_FILES[$item_id]['tmp_name']);
                    }

                    $result   = $Bucket->write_file($_FILES[$item_id]['tmp_name'], $_FILES[$item_id]['name']);

                    $target   = $result['path'];
                    $filename = $result['name'];
                    $filesize = (int)$_FILES[$item_id]['size'];

                    $store['_default'] = rtrim($Bucket->get_web_path(), '/').'/'.$filename;

                    // fire events
                    if ($this->Tag->type()=='image') {
                        $PerchImage = new PerchImage;
                        $profile = $PerchImage->get_resize_profile($target);
                        $profile['original'] = true;
                        $Perch->event('assets.upload_image', new PerchAssetFile($profile));
                    }

                }else{
                    $target = false;
                }


                
            }
        }

        if ($target && $filename && is_file($target)) {

            self::$file_paths[$this->Tag->id()] = $target;

            $store['path']   = $filename;
            $store['size']   = $filesize ?: filesize($target);
            $store['bucket'] = $Bucket->get_name();

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
                    $tmp['mime']     = (isset($result['mime']) ? $result['mime'] : $store['mime']);

                    if (is_array($result) && isset($result['_resourceID'])) {
                        $tmp['assetID'] = $result['_resourceID'];
                    }

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

                    if (is_array($result) && isset($result['_resourceID'])) {
                        $tmp['assetID'] = $result['_resourceID'];
                    }

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

                            if (!$result || !file_exists($result['file_path'])) {
                                $result = $PerchImage->resize_image(self::$file_paths[$Tag->id()], $Tag->width(), $Tag->height(), $Tag->crop());
                            }
                            

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

                                if ($result && isset($result['_resourceID'])) {
                                    $tmp['assetID'] = $result['_resourceID'];
                                }

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
            $parentID = $Resources->log($this->app_id, $store['bucket'], $store['path'], 0, 'orig', false, $store, $AssetMeta);

            // variants
            if (isset($store['sizes']) && PerchUtil::count($store['sizes'])) {
                foreach($store['sizes'] as $key=>$size) {
                    $Resources->log($this->app_id, $store['bucket'], $size['path'], $parentID, $key, false, $size, $AssetMeta);
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


        // Check it's not an empty array
        if (is_array($store) && count($store)===0) {
            return null;
        }

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
                } else {
                    //PerchUtil::debug('Missing variant.');
                    //  This is a bad idea. If there are lots of images, they can't all be resized in the same process.
                    //$item = $this->_generate_variant_on_the_fly($variant_key, $orig_item, $this->Tag);
                }
            }

            if ($this->Tag->output() && $this->Tag->output()!='path') {
                switch($this->Tag->output()) {
                    case 'size':
                        return isset($item['size']) ? $item['size'] : 0;

                    case 'h':
                    case 'height':
                        return isset($item['h']) ? $item['h'] : 0;

                    case 'w':
                    case 'width':
                        return isset($item['w']) ? $item['w'] : 0;

					case 'filename':
						return $item['path'];

                    case 'mime':
                        return $item['mime'];

                    case 'tag':

                        $attrs = [];

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

                        if (isset($orig_item['mime']) && strpos($orig_item['mime'], 'image') === false) {

                            $attrs['href'] = $this->_get_image_src($orig_item, $item);

                            $r =  PerchXMLTag::create('a', 'opening', $attrs, $dont_escape);
                            $r .=  ($this->Tag->is_set('title') ? $this->Tag->title() : $orig_item['title']);
                            $r .= PerchXMLTag::create('a', 'closing');
                            return $r;

                        } else {
                            // include inline?
                            if ($this->Tag->include() == 'inline' && isset($item['mime'])) {
                                if (strpos($item['mime'], 'svg')) {
                                    return file_get_contents($this->_get_image_file($orig_item, $item));
                                    break;
                                }
                            }

                            $attrs['src'] = $this->_get_image_src($orig_item, $item);

                            if (!PERCH_RWD) {
                                $attrs['width']  = isset($item['w']) ? $item['w'] : '';
                                $attrs['height'] = isset($item['h']) ? $item['h'] : '';
                            }

                            if (!isset($attrs['alt'])) {
                                $attrs['alt'] = $orig_item['title'];
                            }

                            return PerchXMLTag::create('img', 'single', $attrs, $dont_escape);
        
                        }

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

    public function get_api_value($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        $out = [
            'title' => isset($raw['title']) ? $raw['title'] : '',
            'src'   => isset($raw['_default']) ? $raw['_default'] : '',
            'path'  => isset($raw['path']) ? $raw['path'] : '',
            'w'     => isset($raw['w']) ? $raw['w'] : '',
            'h'     => isset($raw['h']) ? $raw['h'] : '',
            'mime'  => isset($raw['mime']) ? $raw['mime'] : '',
        ];
        if (isset($raw['bucket'])) {
            $Bucket = PerchResourceBuckets::get($raw['bucket']);
            $out['bucket'] = $Bucket->to_array();
        } else {
            $Bucket = PerchResourceBuckets::get();
        }
        if (isset($raw['sizes'])) {
            $out['sizes'] = [];
            foreach($raw['sizes'] as $key=>$def) {
                $out['sizes'][$key] = [
                    'w'        => isset($def['w']) ? $def['w'] : '',
                    'h'        => isset($def['h']) ? $def['h'] : '',
                    'target_w' => isset($def['target_w']) ? $def['target_w'] : '',
                    'target_h' => isset($def['target_h']) ? $def['target_h'] : '',
                    'density'  => isset($def['density']) ? $def['density'] : '',
                    'path'     => isset($def['path']) ? $def['path'] : '',
                    'size'     => isset($def['size']) ? $def['size'] : '',
                    'mime'     => isset($def['mime']) ? $def['mime'] : '',
                    'src'      => $Bucket->get_web_path_for_file($def['path']),
                ];
            }
        }
        
        return $out;
    }

    public function render_admin_listing($details=false)
    {
        $s = '';

        if (is_array($details)) {

            if ($this->Tag->output()) {
                return $this->get_processed($details);
            }

            $PerchImage = new PerchImage;

            $json = $details;

            $Bucket = PerchResourceBuckets::get($json['bucket']);

            if (isset($json['sizes']['thumb'])) {
                $image_src  = $json['sizes']['thumb']['path'];
                $image_w    = $json['sizes']['thumb']['w'];
                $image_h    = $json['sizes']['thumb']['h'];
            }

            $image_path = PerchUtil::file_path($Bucket->get_file_path().'/'.$image_src);

            if (file_exists($image_path)) {
                $s .= '<img src="'.PerchUtil::html($Bucket->get_web_path().'/'.$image_src).'" width="'.($image_w/2).'" height="'.($image_h/2).'" alt="Preview">';
            }
        }

        return $s;
    }

    public function get_content_summary($details=array(), $Template)
    {
        $id = $this->Tag->input_id();

        if (!PerchUtil::count($details)) return '';

        if (array_key_exists($id, $details)) {
            $raw = $details[$id];
            if (isset($raw['title'])) {
                return PerchUtil::html($raw['title'], true);    
            }
        
        }

        return '';
    }

    private function _generate_variant_on_the_fly($variant_key, $orig, $Tag)
    {
        //PerchUtil::debug($orig);

        if (isset($orig['bucket'])) {
            $Bucket = PerchResourceBuckets::get($orig['bucket']);
        }else{
            $Bucket = PerchResourceBuckets::get($Tag->bucket());
        }

        $file_path = PerchUtil::file_path($Bucket->get_file_path().'/'.str_replace($Bucket->get_file_path().'/', '', $orig['path']));

        $PerchImage = new PerchImage;
        if ($Tag->quality()) $PerchImage->set_quality($Tag->quality());
        if ($Tag->is_set('sharpen')) $PerchImage->set_sharpening($Tag->sharpen());
        if ($Tag->density()) $PerchImage->set_density($Tag->density());

        $result = $PerchImage->resize_image($file_path, $Tag->width(), $Tag->height(), $Tag->crop());

        //PerchUtil::debug($result, 'error');

        if ($result) {
            $item = $result;

            $item['target_w'] = $Tag->width();
            $item['target_h'] = $Tag->height();
            $item['density']  = ($Tag->density() ? $Tag->density() : '1');
            $item['path']     = $item['file_name'];
            $item['size']     = filesize($item['file_path']);
            $item['mime']     = (isset($item['mime']) ? $item['mime'] : '');

            if ($item && isset($item['_resourceID'])) {
                $item['assetID'] = $item['_resourceID'];
            }

            $Assets    = new PerchAssets_Assets;
            $Asset     = $Assets->find($orig['assetID']);

            if ($Asset) {
                $Asset->add_new_size_variant($variant_key, $item);
            }


            return $item;
        }

        return 'bother';
    }

    private function _get_image_src($orig_item, $item)
    {
        //PerchUtil::debug($orig_item, 'success');
        //PerchUtil::debug($item, 'notice');

        if (!isset($item['path'])) return false;

        if (isset($orig_item['bucket'])) {
            $Bucket = PerchResourceBuckets::get($orig_item['bucket']);
        }else{
            $Bucket = PerchResourceBuckets::get($this->Tag->bucket());
        }

        return $Bucket->get_web_path().'/'.str_replace($Bucket->get_web_path().'/', '', $item['path']);
    }

    private function _get_image_file($orig_item, $item)
    {

        if (isset($orig_item['bucket'])) {
            $Bucket = PerchResourceBuckets::get($orig_item['bucket']);
        }else{
            $Bucket = PerchResourceBuckets::get($this->Tag->bucket());
        }

        return PerchUtil::file_path($Bucket->get_file_path().'/'.str_replace($Bucket->get_file_path().'/', '', $item['path']));
    }

    public function get_index($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        $id = $this->Tag->id();

        $out = array();

        if (is_array($raw)) {

            if (isset($raw['_default'])) {
                $out[] = array('key'=>$id, 'value'=>trim($raw['_default']));
            }

        }else{
            $out[] = array('key'=>$id, 'value'=>trim($raw));
        }

        return $out;
    }

    private function _file_is_acceptable($file)
    {   
        if (!PERCH_VERIFY_UPLOADS) return true;

        if (isset($file['error'])) {
            if ($file['error']!=UPLOAD_ERR_OK) {
                return false;
            }
        }

        $File = new PerchAssetFile(array(
                        'file_path' => $file['tmp_name'],
                        'file_name' => $file['name'],
                        'size'      => $file['size'],
                    ));

        $result = $File->is_acceptable_upload($this->Tag, $this->accept_types);

        if (!$result) PerchUtil::debug($File->get_errors(), 'notice');

        #error_log(print_r($File->get_errors(), 1));

        return $result;

    }
}


/* ------------ FILE -- note, extends Image ------------ */

class PerchFieldType_file extends PerchFieldType_image
{
    protected $accept_types = 'pdf,text,richtext,xml,zip,audio,video,office';

    public function render_inputs($details=array())
    {
        $Perch = Perch::fetch();
        $Bucket = PerchResourceBuckets::get($this->Tag->bucket());

        $Assets = new PerchAssets_Assets;

        $s = $this->Form->image($this->Tag->input_id());
        $s .= $this->Form->hidden($this->Tag->input_id().'_field', '1');

        $assetID     = false;
        $Asset       = false;
        $asset_field = $this->Tag->input_id().'_assetID';

        if (isset($details[$this->Tag->input_id()]['assetID'])) {
            $assetID = $details[$this->Tag->input_id()]['assetID'];
        }

        $s .= $this->Form->hidden($asset_field, $assetID);

        $Bucket->initialise();

        if (!$Bucket->ready_to_write()) {
            $s .= $this->Form->hint(PerchLang::get('Your resources folder is not writable. Make this folder (') . PerchUtil::html($Bucket->get_web_path()) . PerchLang::get(') writable to upload files.'), 'error');
        }

        $type = 'doc';
        if ($this->Tag->file_type()) {
            $type = $this->Tag->file_type();
        }

        $Settings = PerchSettings::fetch();
        $permissive = '';
        if (!(int)$Settings->get('assets_restrict_buckets')->val()) {
            $permissive = ' data-permissive-bucketing="true"';
        }

        $add_cta = ' <span class="ft-choose-asset ft-file '.($this->Tag->disable_asset_panel() ? ' assets-disabled' : '').'" data-type="'.$type.'" data-field="'.$asset_field.'" data-bucket="'.PerchUtil::html($Bucket->get_name(), true).'" data-input="'.$this->Tag->input_id().'"'.$permissive.'></span>';

        if (isset($details[$this->Tag->input_id()]) && $details[$this->Tag->input_id()]!='') {
            $json = $details[$this->Tag->input_id()];

            //PerchUtil::debug($json);

            if (isset($json['bucket'])) {
                $Bucket = PerchResourceBuckets::get($json['bucket']);
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

            $file_path = PerchUtil::file_path($Bucket->get_file_path().'/'.$path);

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
                $image_path = PerchUtil::file_path($Bucket->get_file_path().'/'.$image_src);
                $thumb = true;
            } else {
                $thumb = false;
            }

                

            if (file_exists($file_path)) {

                $icon = 'assets/o-document';

                if ($assetID) {
                    $Asset = $Assets->find($assetID);      
                    if ($Asset) {
                        $icon = $Asset->icon_for_type();     
                    }
                }

                $s .= '<div class="asset-badge" data-for="'.$asset_field.'">
                        <div class="asset-badge-inner">';

                $type = PerchAssets_Asset::get_type_from_filename($path);

                
                if ($thumb) {
                    $s .= '<div class="asset-badge-thumb asset-icon icon asset-'.$type.'">';
                    $s .= '<img src="'.PerchUtil::html($Bucket->get_web_path().'/'.$image_src).'" width="'.$image_w.'" height="'.$image_h.'" alt="Preview">';
                } else {
                    $s .= '<div class="asset-badge-thumb thumbless asset-'.$type.'">';
                    $s .= PerchUI::icon($icon, 64);
                }

                if (!$this->Tag->is_set('app_mode')) {
                    // Remove
                    $s .= '<div class="asset-badge-remove">';
                    $s .= '<span class="asset-ban hidden">'.PerchUI::icon('assets/ban-alt', 64).'</span>';
                    $s .= '<span class="asset-badge-remove-fields">';
                    $s .= $this->Form->label($this->Tag->input_id().'_remove', PerchLang::get('Remove'), 'inline'). ' ';
                    $s .= $this->Form->checkbox($this->Tag->input_id().'_remove', '1', 0);
                    $s .= '</span><a href="#" class="asset-badge-remove-action" data-checkbox="'.$this->Tag->input_id().'_remove'.'">'.PerchUI::icon('core/cancel', 24, PerchLang::get('Remove')).'</a>';
                    $s .= '</div>';
                    // End remove
                }



                $s .= '</div>';


                $s .= '<div class="asset-badge-meta">';

                if ($json) {

                    $s .= '<h3 class="title">';
                        if ($Asset) {
                            $s .= '<a href="'.$Asset->web_path().'">';
                        }
                        $s .= (isset($json['title']) ? $json['title'] : str_replace(PERCH_RESPATH.'/', '', $path));
                        if ($Asset) {
                            $s .= '</a>';
                        }
                    $s .= '</h3>';


                    $s .= '<ul class="meta">';

    
                    if ($Asset) {
                        $s .= '<li>'.PerchUI::icon($icon, 12).' ';
                        $s .= $Asset->display_mime().'</li>';
                    } else {
                        if (isset($variant['mime']) && $variant['mime']!='') {
                            $s .= '<li>'.PerchUI::icon($icon, 12).' ';
                            $s .= ucfirst(str_replace('/', ' / ', $variant['mime'])).'</li>';
                        }    
                    }


                    $s .= '<li>'.PerchUI::icon('assets/o-weight-scale', 12).' ';

                     $size     = floatval($json['size']);
                    if ($size < 1048576) {
                        $size = round($size/1024, 0).'<span class="unit">KB</span>';
                    }else{
                        $size = round($size/1024/1024, 0).'<span class="unit">MB</span>';
                    }
                    $s .= $size.'</li>';

                    $s .= '</ul>';

                    $s .= $add_cta;
                } else {
                    $s .= $add_cta;
                }

                $s .= '</div>';
                $s .= '</div>';
                $s .= '</div>';


            }
        }else{

            $s .= '<div class="asset-badge" data-for="'.$asset_field.'">
                        <div class="asset-badge-inner">
                            <div class="asset-badge-thumb thumbless">';
                        $s .= PerchUI::icon('assets/upload', 64, PerchLang::get('Upload'));
                    $s .= '</div>';
                    $s .= '<div class="asset-badge-meta">';
                        $s .= $add_cta;
                    $s .= '</div>';
                $s .= '</div>';
            $s .= '</div>';
        }

        if (isset($file_path) && file_exists($file_path)){
            $s .= $this->Form->hidden($this->Tag->input_id().'_populated', '1');
        }


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
        if (!defined('PERCH_GMAPS_API_KEY')) return;

        $Perch = Perch::fetch();
        $json = [];
        $json['js'] = [
            'https://maps.googleapis.com/maps/api/js?key='.$this->_get_api_key(),
            PERCH_LOGINPATH.'/core/assets/js/maps.js'
        ];

        $Perch->add_fe_plugin('maps', PerchUtil::json_safe_encode($json));
    }

    public function render_inputs($details=array())
    {
        if (!defined('PERCH_GMAPS_API_KEY')) {
            $API = new PerchAPI(1.0, $this->app_id);
            $HTML = $API->get('HTML');
            return $HTML->warning_message(PerchLang::get('To create a map you must set up a Google API key.'));
        }

        $s = $this->Form->text($this->Tag->input_id().'_adr', $this->Form->get((isset($details[$this->Tag->input_id()])? $details[$this->Tag->input_id()] : array()), 'adr', $this->Tag->default()), 'map_adr input-simple m');
        $s .= '<div class="map" data-btn-label="'.PerchLang::get('Find').'" data-mapid="'.PerchUtil::html($this->Tag->input_id(), true).'" data-width="'.($this->Tag->width() ? $this->Tag->width() : '640').'" data-height="'.($this->Tag->height() ? $this->Tag->height() : '360').'">';
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
            $tmp['adr'] = PerchUtil::safe_stripslashes(trim($post[$this->Tag->id().'_adr']));

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

    private function _get_api_key()
    {
        if (!defined('PERCH_GMAPS_API_KEY')) {
            return null;
        }

        return PERCH_GMAPS_API_KEY;
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

                $path = '/maps/api/geocode/json?address='.urlencode($value['adr']).'&sensor=false&key='.$this->_get_api_key();
                $result = PerchUtil::http_get_request('http://', 'maps.googleapis.com', $path);
                if ($result) {
                    $result = PerchUtil::json_safe_decode($result, true);
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
            $r  .= '?key='.PerchUtil::html($this->_get_api_key(), true).'&amp;center='.$clat.','.$clng.'&amp;size='.$static_width.'x'.$static_height.'&amp;scale=2&amp;zoom='.$zoom.'&amp;maptype='.$type;
            if ($lat && $lng)   $r .= '&amp;markers=color:red|color:red|'.$lat.','.$lng;
            $r  .= '" ';
            if ($tag->class())  $r .= ' class="'.PerchUtil::html($tag->class()).'"';
            $r  .= ' width="'.$static_width.'" height="'.$static_height.'" alt="'.PerchUtil::html($adr).'">';

            $out['admin_html'] = $r;

            $map_js_path = PerchUtil::html(PERCH_LOGINPATH, true).'/core/assets/js/public_maps.min.js';
            if (defined('PERCH_MAP_JS') && PERCH_MAP_JS) {
                $map_js_path = PerchUtil::html(PERCH_MAP_JS, true);
            }

            // JavaScript
            $r .= '<script type="text/javascript">/* <![CDATA[ */ ';
            $r .= "if(typeof CMSMap =='undefined'){
                    var CMSMap={};
                    CMSMap.maps=[];
                    var s = document.createElement('script');
                    s.setAttribute('src', '".$map_js_path."');
                    document.body.appendChild(s);
                    }";
            $r .= "CMSMap.maps.push({'mapid':'cmsmap".PerchUtil::html($id)."','width':'".$width."','height':'".$height."','type':'".$type."','zoom':'".$zoom."','adr':'".addslashes(PerchUtil::html($adr))."','lat':'".$lat."','lng':'".$lng."','clat':'".$clat."','clng':'".$clng."'});";
            $r .= "CMSMap.key='".PerchUtil::html($this->_get_api_key(), true)."';";
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

        $region    = $this->Tag->region();
        $field_id  = $this->Tag->options();
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

    public $editFormUnrenderable = true;

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
                    $out[] = trim(PerchUtil::safe_stripslashes($post[$field]));
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

        return parent::render_inputs($details);
    }

    public function get_raw($post=false, $Item=false)
    {
        if ($post===false) {
            $post = PerchRequest::post();
        }

        $id = $this->Tag->id();
        if (isset($post[$id])) {
            $raw = trim($post[$id]);

            $flang = 'plain';

            if ($this->Tag->html()) {
                $flang = 'html';
            }

            $value = PerchUtil::safe_stripslashes($raw);

            // Strip HTML by default
            if (!is_array($value) && PerchUtil::bool_val($this->Tag->html()) == false) {
                $value = PerchUtil::html($value);
                $value = strip_tags($value);
            }

            $Markdown = new PerchParsedown();
            $value = $Markdown->smartypants($value);

            
            $flang = 'smartypants';

            $store = array(
                '_flang'    => $flang,
                'raw'       => $raw,
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
        }
    }

    private function render_select($details, $opts)
    {
        $attributes = $this->Tag->get_data_attribute_string();
       
        $attributes .= ' data-display-as="categories"';

        if ($this->Tag->max()) {
            $attributes .= ' data-max="'.(int)$this->Tag->max().'"';
        }

        $attributes = trim($attributes);

        return $this->Form->select($this->Tag->input_id(), $opts, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), 'input-simple categories '.$this->Tag->size('xxl'), true, $attributes) . $this->render_field_annotations();
    }

    private function render_checkboxes($details, $opts)
    {
        $multicol = 'fieldtype';
        if (PerchUtil::count($opts) > 4) {
            $multicol .= ' multi-col';
        }else{
            $multicol .= ' uni-col';
        }

        return $this->Form->checkbox_set($this->Tag->input_id(), false, $opts, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), false, $this->Tag->max(), $multicol) . $this->render_field_annotations();

    }

    public function import_data($data)
    {
        $id = $this->Tag->id();
        if (array_key_exists($id, $data)) {


            $Categories = new PerchCategories_Categories();
            if (!is_array($data[$id])) {
                $data[$id] = [$data[$id]];
            }
            $catIDs = [];
            foreach($data[$id] as $catPath) {
                $Category = $Categories->get_by_path($catPath);
                if ($Category) {
                    $catIDs[] = $Category->id();
                } else {
                    throw new \Exception('Category not found: '.$catPath);
                }
            }
            //PerchUtil::debug($catIDs);
            $data[$id] = $catIDs;
            return $this->get_raw($data);
        }

        return null;
    }

    public function get_raw($post=false, $Item=false)
    {
        if ($post===false) {
            $post = PerchRequest::post();
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
                $Cat = $Categories->find((int)$catID);
                if ($Cat) $out[] = $Cat->catTitle();
            }

            return implode(', ', $out);
        }

        return $raw;
    }

    public function get_api_value($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        if (is_array($raw) && count($raw)) {
            $out = array();
            $Categories = new PerchCategories_Categories();
            foreach($raw as $catID) {
                if (is_numeric($catID)) {
                    $Cat = $Categories->find((int)$catID);    
                } else {
                    $Cat = $Categories->get_by_path($catID);
                }

                
                if ($Cat) {
                    $out[] = $Cat->to_array_for_api();    
                } 
                
            }

            return $out;
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
                    $Cat = $Categories->find((int)$val);
                    if (is_object($Cat)) $out[] = array('key'=>'_category', 'value'=>$Cat->catPath());
                }
            }

        }


        return $out;
    }

    public function get_content_summary($details=array(), $Template)
    {
        $id = $this->Tag->id();

        if (!PerchUtil::count($details)) return '';

        if (array_key_exists($id, $details)) {
            $raw   = $details[$id];
            if (is_array($raw) && count($raw)) {
                $out = array();
                $Categories = new PerchCategories_Categories();
                foreach($raw as $catID) {
                    if (is_numeric($catID)) {
                        $Cat = $Categories->find((int)$catID);    
                    } else {
                        $Cat = $Categories->get_by_path($catID);
                    }

                    
                    if ($Cat) {
                        $out[] = PerchUtil::html($Cat->catTitle(), true);    
                    } 
                    
                }

                return implode(', ', $out);
            }
        }
    }

}

/* ------------ RELATED ------------ */

class PerchFieldType_related extends PerchFieldType
{
    public function render_inputs($details=array())
    {
        if (!PERCH_RUNWAY) return;

        $mode = 'select';

        if ($this->Tag->display_as() && $this->Tag->display_as()=='checkboxes') {
            $mode = 'checkboxes';
        }

        $collectionKey = $this->Tag->collection();
        if (!$collectionKey) return 'No collection specified';

        $Collections = new PerchContent_Collections();
        $Collection  = $Collections->get_one_by('collectionKey', $collectionKey);

        if (is_object($Collection)) {

            if (isset($details[$this->Tag->id()])) {
                $items = $Collection->get_items_sorted($details[$this->Tag->id()]);
            } else {
                $items = $Collection->get_items();
            }

        }else{
            $items = array();
        }

        $opts = array();
        if (PerchUtil::count($items)) {
            foreach($items as $Item) {
                $opts[] = array('label'=>$Item->get_field('_title'), 'value'=>$Item->itemID());
            }
        }

        switch ($mode) {
            case 'checkboxes':
                return $this->render_checkboxes($details, $opts);
                break;
            default:
                return $this->render_select($details, $opts);
        }
    }

    private function render_select($details, $opts)
    {
        $attributes = $this->Tag->get_data_attribute_string();


        $attributes .= ' data-display-as="categories"';

        if ($this->Tag->max()) {
            $attributes .= ' data-max="'.(int)$this->Tag->max().'"';
        }

        $attributes = trim($attributes);


        return $this->Form->select($this->Tag->input_id(), $opts, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), 'input-simple categories '.$this->Tag->size('xxl'), true, $attributes). $this->render_field_annotations();
    }

    private function render_checkboxes($details, $opts)
    {
        $multicol = 'fieldtype';
        if (PerchUtil::count($opts) > 4) {
            $multicol .= ' multi-col';
        }else{
            $multicol .= ' uni-col';
        }

        return $this->Form->checkbox_set($this->Tag->input_id(), false, $opts, $this->Form->get($details, $this->Tag->id(), $this->Tag->default(), $this->Tag->post_prefix()), false, $this->Tag->max(), $multicol) . $this->render_field_annotations();

    }

    public function get_raw($post=false, $Item=false)
    {
        if ($post===false) {
            $post = PerchRequest::post();
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

        return null;

        if (is_array($raw) && count($raw)) {
            $out = array();
            $Collections = new PerchContent_Collections();
            $Collection  = $Collections->get_one_by('collectionKey', $this->Tag->collection());
            foreach($raw as $itemID) {
                $Cat = $Categories->find((int)$itemID);
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

            $Collections = new PerchContent_Collections();
            $out = $Collections->get_indexed_from_ids($this->Tag->collection(), $raw, $id);

        }


        return $out;
    }

    public function get_content_summary($details=array(), $Template)
    {
        $id = $this->Tag->id();

        if (!PerchUtil::count($details)) return '';

        if (array_key_exists($id, $details)) {
            $raw   = $details[$id];
            if (is_array($raw) && count($raw)) {
                $out = array();
                

                $collectionKey = $this->Tag->collection();
                if (!$collectionKey) return 'No collection specified';

                $Collections = new PerchContent_Collections();
                $Collection  = $Collections->get_one_by('collectionKey', $collectionKey);

                if (is_object($Collection)) {
                    
                    foreach($raw as $itemID) {
                        $items = $Collection->get_items($itemID, 'latest');
                        if (PerchUtil::count($items)) {
                            foreach($items as $Item) {
                                $out[] = PerchUtil::html($Item->get_field('_title'), true);
                            }    
                        }
                        
                        
                    }
                    /*
                    $items = $Collection->get_items_sorted($raw);
                    if (PerchUtil::count($items)) {
                        foreach($items as $Item) {
                            $out[] = json_encode($raw);
                        }
                    }
                    */
                }


                return implode(', ', $out);
            }
        }
    }

}

/* ------------ BLOCKS ------------ */

class PerchFieldType_PerchBlocks extends PerchFieldType
{
    public $field_type_map = [];

    public function get_api_value($raw=false)
    {
        if ($raw===false) $raw = $this->get_raw();

        if (PerchUtil::count($this->field_type_map)) {
            foreach($raw as &$item) {
                foreach($item as $key => &$field) {  
                    if (array_key_exists($key, $this->field_type_map[$item['_block_type']])) {
                        $field = $this->field_type_map[$item['_block_type']][$key]->get_api_value($field);
                    }
                }    
            }
        }
        
        return $raw;
    }
}