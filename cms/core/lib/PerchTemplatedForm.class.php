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
	    $s = '/(<perch:form[^>]*id="'.$formID.'"[^>]*>)(?:(?!perch:form).*)(?:<\/perch:form>)/s';
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
	    $attrs['id']     = $this->field_prefix.$OpeningTag->id();
	    $attrs['class']  = $OpeningTag->class();
	    $attrs['action'] = $OpeningTag->action();
	    $attrs['method'] = $OpeningTag->method();
	    $attrs['role']   = $OpeningTag->role();

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
        
	    if (!$attrs['action']) $attrs['action'] = $Perch->get_page_as_set(true);    

	    // submit via ssl?
	    if (PERCH_SSL && $OpeningTag->ssl() && PerchUtil::bool_val($OpeningTag->ssl())) {
	    	$attrs['action'] = PerchUtil::url_to_ssl($attrs['action']);
	    }

	    if (!$attrs['method']) $attrs['method'] = 'post';

        $this->form_key = base64_encode($this->form_id.':'.$this->handling_app.':'.$this->template_path);
        
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
        $opts = explode(',', $Tag->options());
        
        $value    = $attrs['value'];
        
        unset($attrs['value']);
        unset($attrs['type']);
        
        $new_tag = PerchXMLTag::create('select', 'opening', $attrs);
        
        if (PerchUtil::count($opts)) {
            foreach($opts as $opt) {
                $val = $opt;
                $text = $opt;
                
                if (strpos($opt, '|')) {
                    $parts = explode('|', $opt);
                    $text = $parts[0];
                    $val = $parts[1];
                }
                
                $attrs = array();
                if (trim($val) == $value) $attrs['selected'] = 'selected';
                $attrs['value'] = trim($val);
                
                $new_tag .= PerchXMLTag::create('option', 'opening', $attrs);
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
        $opts = explode(',', $Tag->options());
        
        $value    = $attrs['value'];
        unset($attrs['value']);
        
        $new_tag = '';
        
        $groupID = $attrs['id'];
        
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
        }
        
        return str_replace($match, $new_tag, $template);
    }
    
    
    private function _replace_textarea_field($match, $Tag, $template)
    {
        $attrs = $this->_copy_standard_attributes($Tag);
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
            $new_tag        = PerchXMLTag::create('input', 'single', $attrs);
            return $new_tag;
        }

        return false;
    }
	
	private function _copy_standard_attributes($Tag)
	{
	    $attrs = array();
	    if ($Tag->id()) $attrs['id'] = $this->field_prefix.$Tag->id();
	    $attrs['name']  = $Tag->id();
	    
	    if ($Tag->name()) $attrs['name']  = $Tag->name();
	    
	    $attrs['class']     = $Tag->class();
	    $attrs['value']     = $Tag->value();
	    $attrs['rel']       = $Tag->rel();
	    $attrs['rev']       = $Tag->rev();
	    $attrs['cols']      = $Tag->cols();
        $attrs['rows']      = $Tag->rows();
        $attrs['size']      = $Tag->size();
        $attrs['maxlength'] = $Tag->maxlength();
        $attrs['title']     = $Tag->title();
        $attrs['tabindex']  = $Tag->tabindex();
        $attrs['height']    = $Tag->height();
        $attrs['width']     = $Tag->width();
        

        $aria = $Tag->search_attributes_for('aria-');
        if (PerchUtil::count($aria)) {
        	$attrs = array_merge($attrs, $aria);
        }

        $html5data = $Tag->search_attributes_for('data-');
        if (PerchUtil::count($html5data)) {
        	$attrs = array_merge($attrs, $html5data);
        }

	    
	    if ($Tag->disabled()) $attrs['disabled'] = 'disabled';
	    if ($Tag->checked()) $attrs['checked'] = 'checked';
	    
	    if (PERCH_HTML5) {
			$attrs['type']           = $Tag->type();    
			$attrs['placeholder']    = $Tag->placeholder();
			$attrs['list']           = $Tag->list();
			$attrs['min']            = $Tag->min();
			$attrs['max']            = $Tag->max();
			$attrs['step']           = $Tag->step();
			$attrs['pattern']        = $Tag->pattern();
			$attrs['formaction']     = $Tag->formaction();
			$attrs['formenctype']    = $Tag->formenctype();
			$attrs['formmethod']     = $Tag->formmethod();
			$attrs['formnovalidate'] = $Tag->formnovalidate();
			$attrs['formtarget']     = $Tag->formtarget();
			$attrs['role']           = $Tag->role();

    	    if ($Tag->required())       $attrs['required']      = 'required';
    	    if ($Tag->autocomplete())   $attrs['autocomplete']  = 'autocomplete';
            if ($Tag->autofocus())      $attrs['autofocus']     = 'autofocus';
            if ($Tag->multiple())       $attrs['multiple']      = 'multiple';
            if ($Tag->novalidate())     $attrs['novalidate']    = 'novalidate';
            if ($Tag->readonly())     	$attrs['readonly']    	= 'readonly';

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
		if (isset($_POST[$incoming_attr])) 				$new_value = (stripslashes($_POST[$incoming_attr]));
		if (isset($_GET[$incoming_attr]))  				$new_value = (stripslashes($_GET[$incoming_attr]));

		return $new_value;
	}
	
	
	private function _submitted()
	{
	    if ($this->submitted === null) {
    	    $r = false;
	        if (isset($_POST['cms-form']) && $_POST['cms-form']==$this->form_key) $r = true;
    	    if (isset($_GET['cms-form']) && $_GET['cms-form']==$this->form_key) $r = true;
	    
	        $this->submitted = $r;
	    }
	    
	    return $this->submitted;
	}
}
