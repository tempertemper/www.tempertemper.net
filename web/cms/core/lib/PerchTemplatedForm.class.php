<?php

class PerchTemplatedForm
{
	private $template          = false;
	private $original_template = false;

	private $form_id           = false;
	private $form_key          = false;
	private $handling_app      = false;
	private $template_path     = false;
	private $field_prefix      = false;
	private $action            = false;
	private $app               = false;
	private $method            = false;

    private $counters = array();

	public $form_tag_attributes = false;

	private $content_vars	   = array();

    private $submitted = null;

    function __construct($template_html=false)
	{
		$this->template = $template_html;
		$this->original_template = $template_html;
	}

	public function refine($formID)
	{
	    $contents = $this->template;
	    $s = '/(<perch:form[^>]*id="'.$formID.'"[^>]*>)(?:(?!perch:form).*?)(?:<\/perch:form>)/s';
	    preg_match_all($s, $contents, $match, PREG_SET_ORDER);
        
	    if (PerchUtil::count($match)) {
	        $this->template = $match[0][0];
	        $tag = new PerchXMLTag($match[0][1]);
	        $this->form_tag_attributes = $tag->get_attributes();
	    }
	}

	public function restore()
	{
	    $this->template = $this->original_template;
	}

	public function get_fields()
	{
	    $contents = $this->template;

	    $s = '/<perch:input[^>]*>/';
		$count	= preg_match_all($s, $contents, $matches);

		if ($count > 0) {
		    $out = array();
			foreach($matches[0] as $match) {
				$out[] = new PerchXMLTag($match);
			}
			return $out;
		}
		return false;
	}

	public function render($vars=array())
	{
	    $system_vars = PerchSystem::get_vars();

        if (is_array($system_vars) && is_array($vars)) {
            $vars = array_merge($system_vars, $vars);
        }

		$this->content_vars = $vars;

	    $contents = $this->template;

	    $i = 0;
        while (strpos($contents, 'perch:form')>0 && $i<10) {
            $s = '/(<perch:form[^>]*>)((?!perch:form).*?)(<\/perch:form>)/s';
    		$count	= preg_match_all($s, $contents, $matches, PREG_SET_ORDER);
    		if ($count > 0) {
    			foreach($matches as $match) {
    			    $this->submitted = null;
    			    $contents = $this->_render_form($match[0], $match[1], $match[2], $match[3], $contents, $vars);
    			}
    		}
    		$i++;
    	}

    	return $contents;
	}

	private function _render_form($full_match, $opening_tag, $form_inner, $closing_tag, $contents, $vars)
	{

        $out = $full_match;

        $this->counters = array();

        // Form tags
        $out = $this->_replace_form_tags($out, $opening_tag, $closing_tag);

        // Validation
        if ($this->_submitted()) {
            $out = $this->_show_validation_messages($out);
        }else{
            $out = $this->_strip_all_errors($out);
        }

        // Labels
        $out = $this->_replace_labels($out, $form_inner);

        // Fields
        $out = $this->_replace_inputs($out, $form_inner);

        $s = str_replace($full_match, $out, $contents);
        return $s;
	}


	private function _replace_form_tags($template, $opening_tag, $closing_tag)
	{
	    $Perch = Perch::fetch();

	    $OpeningTag = new PerchXMLTag($opening_tag);

	    if ($OpeningTag->prefix()) {
	        if ($OpeningTag->prefix()=='none') {
	            $this->field_prefix = '';
	        }else{
	            $this->field_prefix = $OpeningTag->prefix().'_';
	        }
	    }else{
	        $Perch->form_count++;
	        $this->field_prefix = 'form'.$Perch->form_count.'_';
	    }

	    $attrs = array();
        $attrs['id']           = $this->field_prefix.$OpeningTag->id();
        $attrs['class']        = $OpeningTag->class();
        $attrs['action']       = $OpeningTag->action();
        $attrs['method']       = $OpeningTag->method();
        $attrs['role']         = $OpeningTag->role();
        $attrs['name']         = $OpeningTag->name();
        $attrs['autocomplete'] = $OpeningTag->autocomplete();

	    $aria = $OpeningTag->search_attributes_for('aria-');
        if (PerchUtil::count($aria)) {
        	$attrs = array_merge($attrs, $aria);
        }

        $html5data = $OpeningTag->search_attributes_for('data-');
        if (PerchUtil::count($html5data)) {
        	$attrs = array_merge($attrs, $html5data);
        }

	    $this->form_id       = $OpeningTag->id();
	    $this->handling_app  = $OpeningTag->app();
	    $this->template_path = $OpeningTag->template();
	    $this->action        = $OpeningTag->action();
	    $this->app           = $OpeningTag->app();
	    $this->method        = $OpeningTag->method();

        if (PERCH_HTML5 && $OpeningTag->novalidate()) $attrs['novalidate'] = 'novalidate';

        if (PERCH_RUNWAY) {
            if (!$attrs['action']) {
                $Runway = PerchRunway::fetch();
                $attrs['action'] = $Runway->get_page();
            }
        }else{
            if (!$attrs['action']) $attrs['action'] = $Perch->get_page_as_set(true);
        }

	    // submit via ssl?
	    if (PERCH_SSL && $OpeningTag->ssl() && PerchUtil::bool_val($OpeningTag->ssl())) {
	    	$attrs['action'] = PerchUtil::url_to_ssl($attrs['action']);
	    }

	    if (!$attrs['method']) $attrs['method'] = 'post';

        $this->form_key = base64_encode($this->form_id.':'.$this->handling_app.':'.$this->template_path.':'.time());

        // Does it have file fields?
        $s = '/(<perch:input[^>]*type="(file|image)"[^>]*>)/s';
	    if(preg_match($s, $template)) $attrs['enctype'] = 'multipart/form-data';


	    $new_opening_tag = PerchXMLTag::create('form', 'opening', $attrs);
	    $template = str_replace($opening_tag, $new_opening_tag, $template);

	    $new_closing_tag = PerchXMLTag::create('form', 'closing');
	    $template = str_replace($closing_tag, $new_closing_tag, $template);
	    return $template;
	}

	private function _strip_all_errors($template)
	{
	    $s = '/(<perch:(error|success)[^>]*>)((?!perch:(error|success)).*?)(<\/perch:(error|success)>)/s';
	    return preg_replace($s, '', $template);
	}

	private function _show_validation_messages($template)
	{
	    $Perch = Perch::fetch();
	    $errors = $Perch->get_form_errors($this->form_id);
	    if (PerchUtil::count($errors)) {

	        // General error message
	        $template = $this->_render_error_message($template, 'all', 'general');

	        foreach($errors as $fieldID=>$type) {
	            $template = $this->_render_error_message($template, $fieldID, $type);
	        }
	    }else{
	        // no errors!
	        if (strpos($template, '<perch:success')!=false) {
	            $s = '/(<perch:success[^>]*>)((?!perch:success).*?)(?:<\/perch:success>)/s';
        	    $count	= preg_match($s, $template, $match);

        	    if ($count) {
        	    	$Tag = new PerchXMLTag($match[1]);
        	    	if ($Tag->show_form()) {
        	    		$template = str_replace($match[0], $match[2], $template);
        	    		return $this->_strip_all_errors($template);
        	    	}

        	    	return $match[2];
        	    }
	        }
	    }

	    return $this->_strip_all_errors($template);
	}

	private function _render_error_message($template, $fieldID, $type)
	{
	    $s = '/(<perch:error[^>]*for="'.$fieldID.'"[^>]*>)((?!perch:error).*?)(?:<\/perch:error>)/s';
	    $count	= preg_match_all($s, $template, $matches, PREG_SET_ORDER);
		if ($count > 0) {
			foreach($matches as $match) {
			    $whole_match = $match[0];
			    $Tag         = new PerchXMLTag($match[1]);
			    $contents    = $match[2];

			    if ($Tag->type()==$type) {
			        $template = str_replace($whole_match, $contents, $template);
			    }
			}
		}

		return $template;
	}

	private function _replace_labels($template, $form_inner)
	{
	    $s = '/(<perch:label[^>]*>)((?!perch:label).*?)(<\/perch:label>)/s';
		$count	= preg_match_all($s, $template, $matches, PREG_SET_ORDER);
		if ($count > 0) {
			foreach($matches as $match) {
			    $template = $this->_replace_label($match[0], $match[1], $match[2], $match[3], $template);
			}
		}

		return $template;
	}

	private function _replace_label($full_match, $opening_tag, $label_inner, $closing_tag, $template)
	{
	    $OpeningTag = new PerchXMLTag($opening_tag);
	    $attrs = array();
	    $attrs['id']    = $OpeningTag->id();
	    $attrs['class'] = $OpeningTag->class();
	    $attrs['for']   = $this->field_prefix.$OpeningTag->for();

	    $new_opening_tag = PerchXMLTag::create('label', 'opening', $attrs);
	    $template = str_replace($opening_tag, $new_opening_tag, $template);

	    $new_closing_tag = PerchXMLTag::create('label', 'closing');
	    $template = str_replace($closing_tag, $new_closing_tag, $template);

	    return $template;
	}

	private function _replace_inputs($template, $form_inner)
	{
	    $s = '/<perch:input[^>]*>/';
		$count	= preg_match_all($s, $template, $matches);

		if ($count > 0) {
			foreach($matches[0] as $match) {
				$Tag = new PerchXMLTag($match);

			    switch($Tag->type())
			    {
                    case 'submit':
                        $template = $this->_replace_submit_field($match, $Tag, $template);
                        break;

                    case 'cms':
                        $template = $this->_replace_cms_field($match, $Tag, $template);
                        break;

                    case 'textarea':
                        $template = $this->_replace_textarea_field($match, $Tag, $template);
                        break;

                    case 'select':
                        $template = $this->_replace_select_field($match, $Tag, $template);
                        break;

                    case 'radio':
                        $template = $this->_replace_radio_field($match, $Tag, $template);
                        break;

                    case 'file':
                    case 'image':
                        $template = $this->_replace_file_field($match, $Tag, $template);
                        break;


			        default:
			            $template = $this->_replace_basic_field($match, $Tag, $template);
			            break;
			    }

			}
		}

		return $template;

	}

    private function _replace_basic_field($match, $Tag, $template)
    {
        $attrs = $this->_copy_standard_attributes($Tag);
        $new_tag = PerchXMLTag::create('input', 'single', $attrs);
        return str_replace($match, $new_tag, $template);
    }

    private function _replace_file_field($match, $Tag, $template)
    {
        $attrs = $this->_copy_standard_attributes($Tag);

        $attrs['type'] = 'file';

        $new_tag = PerchXMLTag::create('input', 'single', $attrs);
        return str_replace($match, $new_tag, $template);
    }

    private function _replace_select_field($match, $Tag, $template)
    {
        $attrs = $this->_copy_standard_attributes($Tag);
        $option_string = $Tag->options();
        $option_string = str_replace('\,', '__COMMA__', $option_string);
        $opts = explode(',', $option_string);

        // Allow empty?
        $s = '';

        if ($Tag->allowempty()) {
        	if ($Tag->placeholder()) {
        		$s .= $Tag->placeholder().'|';
        	}
        	array_unshift($opts, $s);
        }else{
            if ($Tag->placeholder()) {
                $s = '!'.$Tag->placeholder().'|';
                array_unshift($opts, $s);
            }
        }

        $value    = $attrs['value'];

        unset($attrs['value']);
        unset($attrs['type']);

        $new_tag = PerchXMLTag::create('select', 'opening', $attrs);

        if (PerchUtil::count($opts)) {
            foreach($opts as $opt) {

                $attrs = array();

                $opt = str_replace('__COMMA__', ',', $opt);

                if (substr($opt, 0, 1)=='!') {
                    $opt = substr($opt, 1);
                    $attrs['disabled'] = 'disabled';
                }

                $val = $opt;
                $text = $opt;

                if (strpos($opt, '|')) {
                    $parts = explode('|', $opt);
                    $text = $parts[0];
                    $val = $parts[1];
                }

                if (trim($val) == $value) $attrs['selected'] = 'selected';
                $attrs['value'] = trim($val);

                $new_tag .= PerchXMLTag::create('option', 'opening', $attrs, array(), array('value'));
                $new_tag .= PerchUtil::html(trim($text));
                $new_tag .= PerchXMLTag::create('option', 'closing');

            }
        }

        $new_tag .= PerchXMLTag::create('select', 'closing');
        return str_replace($match, $new_tag, $template);
    }

    private function _replace_radio_field($match, $Tag, $template)
    {
        $attrs = $this->_copy_standard_attributes($Tag);
        $opts = array();

        if ($Tag->options()) $opts = explode(',', $Tag->options());

        $value    = $attrs['value'];
        unset($attrs['value']);
        
        $new_tag = '';

        $groupID = $attrs['id'];

        if (!isset($this->counters[$groupID])) {
            $this->counters[$groupID] = 0;
        }

        // wraptags
        $wraptag_open = '';
        $wraptag_close = '';

        if ($Tag->wrap()) {
            if (strpos($Tag->wrap(), '.')) {
                $parts = explode('.', $Tag->wrap());
                $wraptag_open   = PerchXMLTag::create($parts[0], 'opening', array('class'=>$parts[1]));
                $wraptag_close  = PerchXMLTag::create($parts[0], 'closing');
            }else{
                $wraptag_open   = PerchXMLTag::create($Tag->wrap(), 'opening');
                $wraptag_close  = PerchXMLTag::create($Tag->wrap(), 'closing');
            }

            if (isset($attrs['wrap'])) {
            	unset($attrs['wrap']);
            }
        }

        if (PerchUtil::count($opts)) {

            $i = 1;
            foreach($opts as $opt) {
                $thisID = $groupID.$i;
                $val    = $opt;
                $text   = $opt;

                if (strpos($opt, '|')) {
                    $parts = explode('|', $opt);
                    $text = $parts[0];
                    $val = $parts[1];
                }

                $attrs['checked'] = false;
                if (trim($val) == $value) $attrs['checked'] = 'checked';
                $attrs['value'] = trim($val);
                $attrs['type'] = 'radio';
                $attrs['id'] = $thisID;

                $new_tag .= $wraptag_open;
                $new_tag .= PerchXMLTag::create('input', 'single', $attrs);
                $new_tag .= PerchXMLTag::create('label', 'opening', array('for'=>$thisID));
                $new_tag .= PerchUtil::html(trim($text));
                $new_tag .= PerchXMLTag::create('label', 'closing');
                $new_tag .= $wraptag_close;

                $i++;
            }
        }else{
            $thisID = $groupID.$this->counters[$groupID];

            if ($this->counters[$groupID]==0) {
                $attrs['checked'] = 'checked';
            }
            $attrs['value'] = trim($Tag->value());
            $attrs['type'] = 'radio';
            $attrs['id'] = $thisID;

            $new_tag .= $wraptag_open;
            $new_tag .= PerchXMLTag::create('input', 'single', $attrs);
            $new_tag .= $wraptag_close;

        }

        $this->counters[$groupID]++;

        return str_replace($match, $new_tag, $template);
    }


    private function _replace_textarea_field($match, $Tag, $template)
    {
        $attrs  = $this->_copy_standard_attributes($Tag);
        $val    = $attrs['value'];
        unset($attrs['value']);
        unset($attrs['type']);

        if (!$Tag->cols()) $attrs['cols']='30';
        if (!$Tag->rows()) $attrs['rows']='4';

        $new_tag = PerchXMLTag::create('textarea', 'opening', $attrs);
        $new_tag .= PerchUtil::html($val);
        $new_tag .= PerchXMLTag::create('textarea', 'closing');
        return str_replace($match, $new_tag, $template);
    }

    private function _replace_submit_field($match, $Tag, $template)
    {
        $attrs = $this->_copy_standard_attributes($Tag);
        $new_tag = PerchXMLTag::create('input', 'single', $attrs);

        $new_tag .= $this->_get_hidden_cms_field();

        return str_replace($match, $new_tag, $template);
    }

    private function _replace_cms_field($match, $Tag, $template)
    {
        $new_tag = $this->_get_hidden_cms_field();

        return str_replace($match, $new_tag, $template);
    }


    private function _get_hidden_cms_field()
    {
    	if ($this->app) {
            $attrs          = array();
            $attrs['type']  = 'hidden';
            $attrs['name']  = 'cms-form';
            $attrs['value'] = $this->form_key;
            return PerchXMLTag::create('input', 'single', $attrs);
        }

        return false;
    }

	private function _copy_standard_attributes($Tag)
	{
	    $attrs = array();
	    if ($Tag->id()) $attrs['id'] = $this->field_prefix.$Tag->id();
	    $attrs['name']  = $Tag->id();

	    if ($Tag->name()) $attrs['name']  = $Tag->name();

	    $standard_attributes = array(
	    	'accesskey',
	    	'autocapitalize',
	    	'autocorrect',
	    	'class',
	    	'cols',
	    	'height',
	    	'incremental',
	    	'maxlength',
	    	'rel',
	    	'rev',
	    	'rows',
	    	'size',
	    	'tabindex',
	    	'title',
	    	'value',
	    	'width',
	    	);

	    foreach($standard_attributes as $att) {
	    	if (isset($Tag->attributes[$att])) {
	    		$attrs[$att] = $Tag->attributes[$att];
	    	}else{
    			$attrs[$att] = false;
    		}
	    }

        $aria = $Tag->search_attributes_for('aria-');
        if (PerchUtil::count($aria)) {
        	$attrs = array_merge($attrs, $aria);
        }

        $html5data = $Tag->search_attributes_for('data-');
        if (PerchUtil::count($html5data)) {
        	$attrs = array_merge($attrs, $html5data);
        }


		if ($Tag->disabled()) $attrs['disabled'] = 'disabled';
		if ($Tag->checked())  $attrs['checked']  = 'checked';

	    if (PERCH_HTML5) {

	    	$standard_attributes = array(
                'accept',
                'autocomplete',
                'autofocus',
                'autosave',
                'formaction',
                'formenctype',
                'formmethod',
                'formnovalidate',
                'formtarget',
                'hidden',
                'inputmode',
                'list',
                'max',
                'min',
                'minlength',
                'pattern',
                'placeholder',
                'role',
                'selectionDirection',
                'spellcheck',
                'step',
                'type',
                'wrap',
	    		);

	    	foreach($standard_attributes as $att) {
	    		if (isset($Tag->attributes[$att])) {
	    			$attrs[$att] = $Tag->attributes[$att];
	    		}else{
	    			$attrs[$att] = false;
	    		}
	    	}

	    	$boolean_attributes = array(
    			//'autocomplete',
    			'autofocus',
    			'multiple',
    			'novalidate',
    			'readonly',
    			'required',
	    		);

	    	foreach($boolean_attributes as $att) {
	    		if (isset($Tag->attributes[$att])) {
	    			$attrs[$att] = $att;
	    		}
	    	}


	    }else{
	        switch($Tag->type()) {
	            case 'text':
	            case 'password':
	            case 'checkbox':
	            case 'radio':
	            case 'submit':
	            case 'reset':
	            case 'file':
	            case 'hidden':
	            case 'image':
	            case 'button':
                    $attrs['type']  = $Tag->type();
                    break;

                case 'textarea':
                case 'select':
                    $attrs['type'] = false;
                    break;

	            default:
	                $attrs['type']  = 'text';
	                break;
	        }
	    }


        $new_value = $this->_determine_value($Tag);


        switch($Tag->type()) {
            case 'checkbox':
                if (isset($attrs['checked'])) unset($attrs['checked']);
                if ($Tag->value()===$new_value) $attrs['checked'] = 'checked';
                $new_value = false;
                break;
        }


        if ($new_value!==false) $attrs['value'] = $new_value;


	    return $attrs;
	}

	private function _determine_value($Tag)
	{
		$new_value = false;

		$incoming_attr = $Tag->id();
		if ($Tag->name()) $incoming_attr = $Tag->name();

		if (isset($this->content_vars[$incoming_attr])) $new_value = $this->content_vars[$incoming_attr];

        if ($Tag->env_autofill(true)) {
            if (isset($_POST[$incoming_attr]))  $new_value = (stripslashes($_POST[$incoming_attr]));
            if (isset($_GET[$incoming_attr]))   $new_value = (stripslashes($_GET[$incoming_attr]));
        }

		return $new_value;
	}

	private function _submitted()
	{
	    if ($this->submitted === null) {
            $r   = false;
            $key = false;

            if (isset($_POST['cms-form'])) $key = $_POST['cms-form'];
            if (isset($_GET['cms-form']))  $key = $_GET['cms-form'];

            if ($key) {
                $key       = base64_decode($key);
                $parts     = explode(':', $key);
                $formID    = (isset($parts[0]) ? $parts[0] : null);
                $appIDs    = (isset($parts[1]) ? $parts[1] : null);
                $template  = (isset($parts[2]) ? $parts[2] : null);

                if ($formID==$this->form_id && $appIDs==$this->handling_app && $template==$this->template_path) {
                    $r = true;
                }
            }

	        $this->submitted = $r;
	    }

	    return $this->submitted;
	}
}