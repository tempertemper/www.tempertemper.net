<?php

class PerchAPI_Form extends PerchForm
{
    public $app_id = false;
    public $version = 1.0;

    private $Lang = false;

    private $defaults = array();

    public $last = false;

    private $hint = false;

    public $orig_post = array();

    public $add_another = false;
    private $submitted_with_add_another = false;

    function __construct($version=1.0, $app_id, $Lang)
    {
        $this->app_id = $app_id;
        $this->version = $version;
        $this->Lang = $Lang;

        $this->orig_post = $_POST;

        // Include editor plugin
        $dir = PERCH_PATH.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'editors'.DIRECTORY_SEPARATOR.PERCH_APPS_EDITOR_PLUGIN;
        if (is_dir($dir) && is_file($dir.DIRECTORY_SEPARATOR.'_config.inc')) {
            $Perch = Perch::fetch();
            $Perch->add_head_content(str_replace('PERCH_LOGINPATH', PERCH_LOGINPATH, file_get_contents($dir.DIRECTORY_SEPARATOR.'_config.inc')));
        }

        parent::__construct($app_id);
    }

    public function form_start($id=false, $class='magnetic-save-bar')
    {
        $r = '<form method="post" action="'.$this->encode($this->action()).'" ' . $this->enctype();

        if ($id)    $r .= ' id="'.$this->encode($id).'"';
        if ($class) $r .= ' class="app '.$this->encode($class).'"';

        $r .= '>';

        return $r;
    }

    public function form_end($submit_button=false)
    {
        $s = '';

        if ($submit_button) {
            $s .= $this->submit_field();
        }

        $s .= '</form>';

        return $s;
    }

    public function form_complete($Template, $Item)
    {
        $s = '';

        $details = array();
        if (is_object($Item)) $details = $Item->to_array();

        echo $this->form_start();
        echo $this->fields_from_template($Template, $details);
        echo $this->form_end(true);

        return $s;
    }

    public function receive($postvars)
	{
	    $data = array();
	    foreach($postvars as $val){
	        if (isset($_POST[$val])) {
	            if (!is_array($_POST[$val])){
	                $data[$val]	= trim(PerchUtil::safe_stripslashes($_POST[$val]));
	            }else{
	                $data[$val]	= $_POST[$val];
	            }
	        }
	    }

	    return $data;
	}

    public function require_field($id, $message)
    {
        $this->required[$id] = $message;
    }

    public function submitted()
    {
        if (isset($_POST['add_another']) && $_POST['add_another']!='') {
            $this->submitted_with_add_another = true;
        }

        return $this->posted() && $this->validate();
    }

    public function submitted_with_add_another()
    {
        return $this->submitted_with_add_another;
    }

    public function text_field($id, $label, $value='', $class='', $limit=false, $attributes='')
    {
        $out = $this->field_start($id);
        $out .= $this->label($id, $this->Lang->get($label), '', $colon=false, $translate=false);
        $out .= $this->text($id, $this->get_value($id, $value), $class, $limit, 'text', $attributes);
        $out .= $this->field_end($id);

        return $out;
    }

    public function textarea_field($id, $label, $value='', $class='', $use_editor_or_template_tag=true)
    {
        $data_atrs = array();

        if (is_object($use_editor_or_template_tag)) {
            $tag = $use_editor_or_template_tag;

            $class .= ' large ';
            if ($tag->editor()) $class .= $tag->editor();
            if ($tag->textile()) $class .= ' textile';
            if ($tag->markdown()) $class .= ' markdown';
            if ($tag->size()) $class .= ' '.$tag->size();
            if (!$tag->textile() && !$tag->markdown() && $tag->html()) $class .= ' html';

            if ($tag->imagewidth()) $data_atrs['width'] = $tag->imagewidth();
            if ($tag->imageheight()) $data_atrs['height'] = $tag->imageheight();
            if ($tag->imagecrop()) $data_atrs['crop'] = $tag->imagecrop();
            if ($tag->imageclasses()) $data_atrs['classes'] = $tag->imageclasses();
            if ($tag->bucket()) $data_atrs['bucket'] = $tag->bucket();
        }

        if ($use_editor_or_template_tag && !is_object($use_editor_or_template_tag)) {
            $class .= ' large '.PERCH_APPS_EDITOR_PLUGIN.' '.PERCH_APPS_EDITOR_MARKUP_LANGUAGE;
        }

        $out = $this->field_start($id);
        $out .= $this->label($id, $this->Lang->get($label), '', $colon=false, $translate=false);
        $out .= $this->textarea($id, $this->get_value($id, $value), $class, $data_atrs);
        $out .= $this->field_end($id);

        return $out;
    }

    public function date_field($id, $label, $value='', $time=false)
    {
        $out = $this->field_start($id);
        $out .= $this->label($id, $this->Lang->get($label), '', $colon=false, $translate=false);
        if ($time) {
            $out .= $this->datetimepicker($id, $this->get_value($id, $value));
        }else{
            $out .= $this->datepicker($id, $this->get_value($id, $value));
        }

        $out .= $this->field_end($id);

        return $out;
    }

    public function image_field($id, $label, $value='', $basePath='', $class='')
    {
        $out = $this->field_start($id);
        $out .= $this->label($id, $this->Lang->get($label), '', $colon=false, $translate=false);
        $out .= $this->image($id, $value, $basePath, $class);
        if ($value!='') {
            $out .= '<img class="preview" src="'.PerchUtil::html($value).'" alt="'.PerchLang::get('Preview').'" />';
            $out .= '<div class="remove">';
            $out .= $this->checkbox($id.'_remove', '1', 0).' '.$this->label($id.'_remove', PerchLang::get('Remove image'), 'inline');
            $out .= '</div>';
        }
		$out .= $this->field_end($id);

        return $out;
    }

    public function file_field($id, $label, $value='', $basePath='', $class='')
    {
        $out = $this->field_start($id);
        $out .= $this->label($id, $this->Lang->get($label), '', $colon=false, $translate=false);
        $out .= $this->image($id, $value, $basePath, $class);
        if ($value!='') {
            $out .= '<div class="file icon">'.PerchUtil::html(str_replace(PERCH_RESPATH.'/', '', $value)).'</div>';
            $out .= '<div class="remove">';
            $out .= $this->checkbox($id.'_remove', '1', 0).' '.$this->label($id.'_remove', PerchLang::get('Remove file'), 'inline');
            $out .= '</div>';
        }
		$out .= $this->field_end($id);

        return $out;
    }

    public function select_field($id, $label, $options, $value='', $class='')
    {
        $out = $this->field_start($id);
        $out .= $this->label($id, $this->Lang->get($label), '', $colon=false, $translate=false);
        $out .= $this->select($id, $options, $this->get_value($id, $value), $class);
        $out .= $this->field_end($id);

        return $out;
    }

    public function grouped_select_field($id, $label, $options, $value='', $class='')
    {
        $out = $this->field_start($id);
        $out .= $this->label($id, $this->Lang->get($label), '', $colon=false, $translate=false);
        $out .= $this->grouped_select($id, $options, $this->get_value($id, $value), $class);
        $out .= $this->field_end($id);

        return $out;
    }


    public function checkbox_field($id, $label, $checked_value='1', $value='', $class='', $limit=false)
    {
        $out = $this->field_start($id);
        $out .= $this->label($id, $this->Lang->get($label), '', $colon=false, $translate=false);
        $out .= $this->checkbox($id, $checked_value, $this->get_value($id, $value), $class, $limit);
        $out .= $this->field_end($id);

        return $out;
    }


    public function checkbox_set($id, $label, $options, $values=array(), $class='', $limit=false, $container_class='')
    {
        $out = '';
        if ($label!==false) $out = $this->field_start($id);

        if (!$values) $values = array();

        $out .= '<fieldset>';
        $out .= '<div class="wrapped checkboxes '.$container_class.'"><strong>'.PerchUtil::html($this->Lang->get($label)).'</strong>';
        $i = 0;

        foreach($options as $option) {
            $boxid = $id.'_'.$i;
            $checked_value = false;
            if (in_array($option['value'], $values)){
                $checked_value = $option['value'];
            }
            if (PerchUtil::count($_POST)) {
                $checked_value = false;
                if (isset($_POST[$id]) && is_array($_POST[$id])) {
                    if (in_array($option['value'], $_POST[$id])) {
                        $checked_value = $option['value'];
                    }
                }
            }

            $out .= '<div class="checkbox">';
            $out .= $this->checkbox($boxid, $option['value'], $checked_value, $class, $id);
            $out .= $this->label($boxid, $option['label'], '', $colon=false, $translate=false);
            $out .= '</div>';
            $i++;
        }


        $out .= '</div>';
        $out .= '</fieldset>';
        if ($label!==false) $out .= $this->field_end($id);

        return $out;
    }


    public function submit_field($id='btnSubmit', $value="Save Changes", $cancel_url=false, $class='button')
    {
        $out = $this->submit_start();

		$out .= $this->submit($id, $this->Lang->get($value), $class, $translate=false);

        if ($this->add_another) {
            $out .= $this->submit('add_another', $this->Lang->get('Save & Add another'), 'button', $translate=false);
        }

		if ($cancel_url) {
		    $out .= ' ' . $this->Lang->get('or') . ' <a href="'.$this->encode($cancel_url).'">' . $this->Lang->get('Cancel'). '</a>';
		}



        $out .= $this->submit_end();

        return $out;
    }

    public function field_start($id)
    {
        $r = '<div class="field '. $this->error($id, false). ($this->last ? ' last' : '').'">';
        $this->last = false;
        return $r;
    }

    public function field_end($id)
    {
        $r = '';

        if ($this->hint) $r .= parent::hint($this->hint);

        $r .= '</div>';

        $this->hint = false;

        return $r;
    }

    public function hint($string, $class=false)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        $this->hint = $string;
    }


    public function field_help($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return parent::hint($string);
    }

    public function submit_start()
    {
        $s = '<p class="submit';
        if (defined('PERCH_NONSTICK_BUTTONS') && PERCH_NONSTICK_BUTTONS) {
            $s .= ' nonstick';
        }

        $s .= '">';
        return $s;
    }

    public function submit_end()
    {
        return '</p>';
    }

    public function encode($string)
    {
        return PerchUtil::html($string);
    }

    public function set_defaults($defaults)
    {
        $this->defaults = $defaults;
    }

    public function get_value($id, $value, $array=false)
    {
        if (!$array) $array = $this->defaults;

        return $this->get($array, $id, $value);
    }

    public function set_required_fields_from_template($Template, $details=array(), $seen_tags=array())
    {
        $tags       = $Template->find_all_tags_and_repeaters();

        if (is_array($tags)) {
            PerchContent_Util::set_required_fields($this, null, $details, $tags, $Template);
        }

        // init editors
        $tags       = $Template->find_all_tags();
        if (PerchUtil::count($tags)) {
            foreach($tags as $Tag) {
                if ($Tag->type()) PerchFieldTypes::get($Tag->type(), $this, $Tag, $tags, $this->app_id);
            }
        }


    }

    public function fields_from_template($Template, $details=array(), $seen_tags=array(), $include_repeaters=true)
    {
        if ($include_repeaters) {
            $tags   = $Template->find_all_tags_and_repeaters();
        }else{
            $tags   = $Template->find_all_tags();
        }

        $Form = $this;

        $out = '';

        if (PerchUtil::count($tags)) {
            PerchContent_Util::display_item_fields($tags, null, $details, false, $Form, $Template, array('PerchAPI_Form', 'get_block_link'), $seen_tags);
        }

        return $out;
    }

    public static function get_block_link($qs)
    {
        return '?'.http_build_query($qs);
    }

    public function receive_from_template_fields($Template, $previous_values, $Factory=false, $Item=false, $clear_post=true, $strip_static=true)
    {
        $tags   = $Template->find_all_tags_and_repeaters();

        if (is_object($Item)) {
            $Item->squirrel('itemID', '');
            $Item->squirrel('itemRowID', '');
        }else{
            $Item = $Factory->return_instance(array(
                    'itemID' => '',
                    'itemRowID' => '',
                ));
            $Item->set_null_id();
        }

        $Item->squirrel('itemJSON', PerchUtil::json_safe_encode($previous_values));

        $subprefix   = '';
        $postitems   = $this->find_items('perch_');
        $form_vars   = array();
        $options     = array();
        $search_text = ' ';

        $API = new PerchAPI(1.0, $this->app_id);
        $Resources = $API->get('Resources');

        list($form_vars, $search_text) = PerchContent_Util::read_items_from_post($Item, $tags, $subprefix, $form_vars, $postitems, $this, $search_text, $options, $Resources, false, $Template);

        if (isset($form_vars['_blocks'])) {
            $form_vars['_blocks'] = PerchUtil::array_sort($form_vars['_blocks'], '_block_index');
        }

        $out = array();
        if ($strip_static) {
            if (PerchUtil::count($form_vars)) {
                foreach($form_vars as $key=>$val) {
                    if (!in_array($key, $Factory->static_fields)) {
                        $out[$key]=$val;
                    }
                }
            }
        }else{
            $out = $form_vars;
        }


        // Clear values from Post (for reordering of blocks etc)
        if ($clear_post) $_POST = array();

        return $out;
    }

    public function get_posted_content($Template, $Factory, $Item=false, $include_repeaters=true, $json_encode=true)
    {
        $data = array();

        $prev = false;
        if ($Item) $prev = $Item->to_array();

        $dynamic_fields = $this->receive_from_template_fields($Template, $prev, $Factory, $Item, true, false);

        $static_fields = array();

        // fetch out static fields
        foreach($Factory->static_fields as $field) {
            if (array_key_exists($field, $dynamic_fields)) { //($dynamic_fields[$field])) {
                if (is_array($dynamic_fields[$field])) {
                    if (isset($dynamic_fields[$field]['_default'])) {
                        $data[$field] = trim($dynamic_fields[$field]['_default']);
                    }

                    if (isset($dynamic_fields[$field]['processed'])) {
                        $data[$field] = trim($dynamic_fields[$field]['processed']);
                    }
                }

                if (!isset($data[$field])) $data[$field] = $dynamic_fields[$field];
                unset($dynamic_fields[$field]);
            }else{
                if (isset($_POST[$field])) {
                    if (!is_array($_POST[$field])){
                        $data[$field] = trim(PerchUtil::safe_stripslashes($_POST[$field]));
                    }else{
                        $data[$field] = $_POST[$field];
                    }
                }
            }
        }

        if (!$json_encode) return $dynamic_fields;

        $data[$Factory->dynamic_fields_column] = PerchUtil::json_safe_encode($dynamic_fields);

        return $data;
    }

    public function post_process_field($tag, $value)
    {
        $out = array();

        $out[$tag->id()] = $value;

        return $out;
    }

    public function handle_empty_block_generation($Template)
    {
        if (PerchUtil::post('add-block')) {
            echo PerchContent_Util::get_empty_block(null, PerchUtil::post('add-block'), (int)PerchUtil::post('count'), false, $Template, $this);
            exit;
        }
    }

    public function old_set_required_fields_from_template($Template, $seen_tags=array())
    {
        $tags       = $Template->find_all_tags();

        if (is_array($tags)) {
            foreach($tags as $tag) {

                // initialising the field type here makes sure editor plugins are kicked of in the <head>
                $FieldType = PerchFieldTypes::get($tag->type(), $this, $tag, $tags, $this->app_id);

                $item_id = 'perch_'.$tag->id();
                if (!in_array($tag->id(), $seen_tags)) {
                    if (PerchUtil::bool_val($tag->required())) {
                        if ($tag->type() == 'date') {
                            if ($tag->time()) {
                                $this->require_field($item_id.'_minute', "Required");
                            }else{
                                $this->require_field($item_id.'_year', "Required");
                            }
                        }else{
                            $this->require_field($item_id, "Required");
                        }
                    }
                    $seen_tags[] = $tag->id();
                }
            }
        }
    }

    public function old_fields_from_template($Template, $details=array(), $seen_tags=array(), $include_repeaters=true)
    {
        if ($include_repeaters) {
            $tags   = $Template->find_all_tags_and_repeaters();
        }else{
            $tags   = $Template->find_all_tags();
        }


        $Form = $this;

        $out = '';

        if (PerchUtil::count($tags)) {
            foreach($tags as $tag) {

                $item_id = 'perch_'.$tag->id();
                $raw_id = 'perch_'.$tag->id().'_raw';

                $tag->set('input_id', $item_id);
                $tag->set('post_prefix', 'perch_');

                if (!in_array($tag->id(), $seen_tags) && $tag->type()!='hidden') {

                    if ($tag->type()=='slug' && !$tag->editable()) {
                        continue;
                    }

                    if ($tag->divider_before()) {
                       $out .= '<h2 class="divider">'.PerchUtil::html($tag->divider_before()).'</h2>';
                    }

                    $out .= '<div class="field '.$Form->error($item_id, false).'">';

                    $label_text  = PerchUtil::html($tag->label());
                    if ($tag->type() == 'textarea') {
                        if (PerchUtil::bool_val($tag->textile()) == true) {
                            $label_text .= ' <span><a href="'.PERCH_LOGINPATH.'/core/help/textile" class="assist">Textile</a></span>';
                        }
                        if (PerchUtil::bool_val($tag->markdown()) == true) {
                            $label_text .= ' <span><a href="'.PERCH_LOGINPATH.'/core/help/markdown" class="assist">Markdown</a></span>';
                        }
                    }
                    $Form->disable_html_encoding();
                    $out .= $Form->label($item_id, $label_text, '', false, false);
                    $Form->enable_html_encoding();


                        $FieldType = PerchFieldTypes::get($tag->type(), $Form, $tag, $tags, $this->app_id);

                        $out.= $FieldType->render_inputs($details);

                    if ($tag->help()) {
                        $out .= $Form->field_help($tag->help());
                    }


                    $out .= '</div>';

                    if ($tag->divider_after()) {
                       $out .= '<h2 class="divider">'.PerchUtil::html($tag->divider_after()).'</h2>';
                    }

                    $seen_tags[] = $tag->id();
                }
            }

        }

        return $out;
    }

    public function old_receive_from_template_fields($Template, $previous_values, $perch_only=true, $fixed_fields=false, $include_repeaters=true)
    {
        if ($include_repeaters) {
            $tags   = $Template->find_all_tags_and_repeaters();
        }else{
            $tags   = $Template->find_all_tags();
        }

        $Form = $this;

        $form_vars = array();

        if (is_array($tags)) {

            $seen_tags = array();

            if ($perch_only) {
                $postitems = $Form->find_items('perch_');

                $seen_tags = array_keys($_POST);

                //if (!$postitems) $postitems = array();
                //$postitems = array_merge($_POST, $postitems);

                if (!$postitems) {
                    $postitems = $_POST;
                }

            }else{
                $postitems = $_POST;
            }

            foreach($tags as $tag) {
                $item_id = 'perch_'.$tag->id();

                $tag->set('input_id', $item_id);

                if (!in_array($tag->id(), $seen_tags)) {
                    $var = false;


                    $FieldType = PerchFieldTypes::get($tag->type(), $Form, $tag, $tags, $this->app_id);

                    $var = $FieldType->get_raw($postitems, $previous_values);

                    if (true || $var) {

                        $form_vars[$tag->id()] = $var;

                        // title
                        if ($tag->title()) {
                            $title_var = $var;

                            if (is_array($var) && isset($var['_title'])) {
                                $title_var = $var['_title'];
                            }

                            if (isset($forms_vars[$i])) {
                                if (isset($form_vars[$i]['_title'])) {
                                    $form_vars[$i]['_title'] .= ' '.$title_var;
                                    $processed_vars[$i]['_title'] = ' '.$title_var;
                                }else{
                                    $form_vars[$i]['_title'] = $title_var;
                                    $processed_vars[$i]['_title'] = $title_var;
                                }
                            }

                        }
                    }
                    $seen_tags[] = $tag->id();
                }
            }



        }

        return $form_vars;
    }


}