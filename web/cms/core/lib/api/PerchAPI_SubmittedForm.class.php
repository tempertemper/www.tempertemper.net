<?php

class PerchAPI_SubmittedForm
{
    public $app_id = false;
    public $version = 1.0;

    private $Lang = false;

    public $data = array();
    public $files = array();
    public $antispam = false;
    public $form_attributes = array();
    public $page = false;

    public $id;

    public $formID;
    public $templatePath;
    private $templateContent = false;

    private $filetypes = array();
    private $timestamp = false;
    public $mimetypes = array();

    public $redispatched = false;


    function __construct($version=1.0, $app_id, $Lang)
    {
        $this->app_id  = $app_id;
        $this->version = $version;
        $this->Lang    = $Lang;

        $Perch         = Perch::fetch();
        $this->page    = $Perch->get_page_as_set(1);
    }

    public function populate($formID, $templatePath, $data, $files, $timestamp=false)
    {
        $this->formID       = $formID;
        $this->id           = $formID;
        $this->templatePath = $templatePath;
        $this->timestamp    = $timestamp;

        if (PerchUtil::count($data)) {
            foreach($data as &$datum) {
                $datum = PerchUtil::safe_stripslashes($datum);
            }
        }

        $this->data  = $data;
        $this->files = $files;
    }

    public function throw_error($type, $field='all')
    {
        $Perch = Perch::fetch();
        $Perch->log_form_error($this->formID, $field, $type);
    }

    public function validate()
    {
        // check timestamp - stop form being submitted too quickly
        if ($this->timestamp > 0) {
            // reject if the form was submitted less than 1 second after being generated
            if (time() - (int) $this->timestamp < 1) {
                PerchUtil::debug('🤖 Form submitted too quickly - are you a robot?', 'error');
                return false;
            }
        }

        $valid = true;

        if (file_exists(PerchUtil::file_path(PERCH_PATH.$this->templatePath))){

            $template = $this->_get_template_content();

       		$TemplatedForm = new PerchTemplatedForm($template);

			$TemplatedForm->refine($this->formID);
			$fields = $TemplatedForm->get_fields();
            $this->form_attributes = $TemplatedForm->form_tag_attributes;


			if (PerchUtil::count($fields)) {
			    $Perch = Perch::fetch();

			    $check_format = function_exists('filter_var');

			    if (PerchUtil::count($_FILES)) {
			        $this->filetypes = $this->_parse_filetypes_file();
			    }

			    foreach($fields as $Tag) {

			        $incoming_attr = $Tag->id();
		            if ($Tag->name()) $incoming_attr = $Tag->name();

			        // Required
			        if ($Tag->required()) {
			            if (!isset($_POST[$incoming_attr]) || $_POST[$incoming_attr]=='') {
			                if (!isset($_GET[$incoming_attr]) || $_GET[$incoming_attr]=='') {
			                    if (!isset($_FILES[$incoming_attr]) || $_FILES[$incoming_attr]=='' || (isset($_FILES[$incoming_attr]['size']) && $_FILES[$incoming_attr]['size']==0)) {
    			                    $valid = false;
    			                    $Perch->log_form_error($this->formID, $Tag->id(), 'required');
    			                }
    			            }
			            }
			        }

			        // Format
			        if ($check_format) {
			            $val = '';

			            if (isset($_POST[$incoming_attr]) && $_POST[$incoming_attr]!='') {
			                $val = trim($_POST[$incoming_attr]);
			            }else{
			                if (isset($_GET[$incoming_attr]) && $_GET[$incoming_attr]!='') {
			                    $val = trim($_GET[$incoming_attr]);
			                }
			            }

			            if ($val != '') {
        			        switch ($Tag->type()) {
        			            case 'email':
    			                    if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
    			                        $valid = false;
    			                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
    			                    }
        			                break;

        			            case 'url':
			                        if (!filter_var($val, FILTER_VALIDATE_URL)) {
    			                        $valid = false;
    			                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
    			                    }
        			                break;

        			            case 'number':
        			            case 'range':
        			                if (filter_var($val, FILTER_VALIDATE_FLOAT)!==false) {
        			                    $val = (float)$val;

        			                    // min
    			                        if ($Tag->is_set('min') && $val<(float)$Tag->min()) {
    			                            $valid = false;
        			                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
    			                        }

        			                    // max
    			                        if ($Tag->is_set('max') && $val>(float)$Tag->max()) {
    			                            $valid = false;
        			                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
    			                        }

                                        // step
                                        $min = 0;
                                        if ($Tag->is_set('min')) $min = (float)$Tag->min();
                                        if ($Tag->step() && strtolower($Tag->step())!='any' && ($val-$min)%(float)$Tag->step()>0) {
                                            $valid = false;
        			                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
                                        }

        			                }else{
        			                    $valid = false;
    			                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
        			                }
        			                break;

        			            case 'color':
            			            if (!filter_var($val, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^#[0-9a-fA-F]{6}$/")))) {
    			                        $valid = false;
    			                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
    			                    }
        			                break;

                                case 'week':
                                    $pattern = '/^[0-9]{4}-W[0-9]{1,2}$/';
                                    if (!filter_var($val, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern)))) {
                                        $valid = false;
                                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
                                    }
                                    break;

                                case 'month':
                                    $pattern = '/^[0-9]{4}-[0-9]{1,2}$/';
                                    if (!filter_var($val, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern)))) {
                                        $valid = false;
                                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
                                    }
                                    break;

                                case 'date':
                                    $pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
                                    if (!filter_var($val, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern)))) {
                                        $valid = false;
                                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
                                    }
                                    break;

                                case 'datetime':
                                    $pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{1,2}:[0-9]{2}:{0,1}[0-9]{0,2}$/';
                                    if (!filter_var($val, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern)))) {
                                        $valid = false;
                                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
                                    }
                                    break;

                                case 'time':
                                    $pattern = '/^[0-9]{1,2}:[0-9]{2}:{0,1}[0-9]{0,2}$/';
                                    if (!filter_var($val, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern)))) {
                                        $valid = false;
                                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
                                    }
                                    break;
        			        }


        			        // Pattern
        			        if ($Tag->pattern()) {
        			            if (!filter_var($val, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>'/^'.$Tag->pattern().'$/')))) {
			                        $valid = false;
			                        $Perch->log_form_error($this->formID, $Tag->id(), 'format');
			                    }
        			        }
        			    }
    			    }

                    // Match with
                    if ($Tag->match_with()) {
                        $field1 = '';
                        if ((isset($_POST[$incoming_attr]) && $_POST[$incoming_attr]!='')){
                            $field1 = $_POST[$incoming_attr];
                        }else{
                            if ((isset($_GET[$incoming_attr]) && $_GET[$incoming_attr]!='')) {
                                $field1 = $_GET[$incoming_attr];
                            }
                        }

                        $field2 = '';
                        if ((isset($_POST[$Tag->match_with()]) && $_POST[$Tag->match_with()]!='')){
                            $field2 = $_POST[$Tag->match_with()];
                        }else{
                            if ((isset($_GET[$Tag->match_with()]) && $_GET[$Tag->match_with()]!='')) {
                                $field2 = $_GET[$Tag->match_with()];
                            }
                        }

                        if ($field1 != $field2) {
                            $valid = false;
                            $Perch->log_form_error($this->formID, $Tag->id(), 'match');
                        }else{
                            unset($this->data[$Tag->match_with()]);
                        }
                    }

                    // Helpers
                    if ($Tag->helper()) {
                        $field = false;
                        if ((isset($_POST[$incoming_attr]) && $_POST[$incoming_attr]!='')){
                            $field = $_POST[$incoming_attr];
                        }else{
                            if ((isset($_GET[$incoming_attr]) && $_GET[$incoming_attr]!='')) {
                                $field = $_GET[$incoming_attr];
                            }
                        }

                        if ($field!==false) {
                            $helper = $Tag->helper();
                            $helper = explode('::', $helper);
                            if (!call_user_func(array($helper[0], $helper[1]), $field)) {
                                $Perch->log_form_error($this->formID, $Tag->id(), 'helper');
                                $valid = false;
                            }
                        }
                    }

			        // Files - mime check
			        if ($Tag->type()=='image') {
			            $accept = 'image';
			        }else{
			            $accept = $Tag->accept();
			        }
			        if ($accept && isset($_FILES[$incoming_attr]) && $_FILES[$incoming_attr]['size']>0 && $_FILES[$incoming_attr]['error']==0) {
			            $mime_type = $this->_get_mime_type($_FILES[$incoming_attr]['tmp_name']);
			            $this->mimetypes[$incoming_attr] = $mime_type;
			            $parts = explode('/', $mime_type);
			            $mime_type_wildcarded = $parts[0].'/*';
                        if (strpos($accept, ',')) {
                            $arr_accept = explode(',', $accept);    
                        }else{
                            $arr_accept = explode(' ', $accept);
                        }
			            $found = false;
			            if (PerchUtil::count($arr_accept)) {
			                foreach($arr_accept as $type) {
                                $type = trim($type);
			                    if (isset($this->filetypes[$type])) {
			                        if (in_array($mime_type, $this->filetypes[$type]) || in_array($mime_type_wildcarded, $this->filetypes[$type])) {
			                            $found = true;
			                            break;
			                        }
			                    }
			                }
			            }
			            if (!$found) {
			                $valid = false;
                            $Perch->log_form_error($this->formID, $Tag->id(), 'filetype');
			            }
			        }

			        // Files - upload error check
			        if (isset($_FILES[$incoming_attr]) && $_FILES[$incoming_attr]['error']>0 && $_FILES[$incoming_attr]['error']!=UPLOAD_ERR_NO_FILE) {
			            $valid = false;
                        $Perch->log_form_error($this->formID, $Tag->id(), 'fileupload');
			        }
			    }
			}

		}
        return $valid;
    }

    public function get_antispam_values()
    {
        if ($this->antispam!==false) {
            return $this->antispam;
        }

        $antispam = array();

        if (file_exists(PerchUtil::file_path(PERCH_PATH.$this->templatePath))){
			$template = $this->_get_template_content();
			$TemplatedForm = new PerchTemplatedForm($template);

			$TemplatedForm->refine($this->formID);
			$fields = $TemplatedForm->get_fields();

			if (PerchUtil::count($fields)) {
			    foreach($fields as $Tag) {
			        if ($Tag->antispam()) {
			            $key = $Tag->antispam();

			            $incoming_attr = $Tag->id();
    		            if ($Tag->name()) $incoming_attr = $Tag->name();

			            if (isset($this->data[$incoming_attr])) {
			                if (isset($antispam[$key])) {
    			                $antispam[$key] .= ' '.$this->data[$incoming_attr];
    			            }else{
    			                $antispam[$key] = $this->data[$incoming_attr];
    			            }
			            }

			        }
			    }
			}
		}

		$this->antispam  = $antispam;

		return $antispam;
    }

    public function get_template_attributes($fieldID)
    {
        $template = $this->_get_template_content();
        $s = '/(<perch:input[^>]*id="'.$fieldID.'"[^>]*>)/s';
        $count = preg_match($s, $template, $match);
        if ($count) {
            return new PerchXMLTag($match[0]);
        }

        // if ID doesn't work, try name
        $s = '/(<perch:input[^>]*name="'.$fieldID.'"[^>]*>)/s';
        $count = preg_match($s, $template, $match);
        if ($count) {
            return new PerchXMLTag($match[0]);
        }

        return false;
    }

    public function get_attribute_map($attribute, $reverse_output=false)
    {
        $template = $this->_get_template_content();
        $s = '/(<perch:input[^>]* '.$attribute.'="[^>]*>)/s';
        preg_match_all($s, $template, $matches, PREG_SET_ORDER);
        $out = array();
        if ($matches) {
            $attribute = str_replace('-', '_', $attribute);
            foreach($matches as $match) {
                $Tag = new PerchXMLTag($match[0]);
                if ($reverse_output) {
                    $out[$Tag->$attribute()] = $Tag->id();
                }else{
                    $out[$Tag->id()] = $Tag->$attribute();
                }
            }
        }
        return $out;
    }

    public function get_form_attributes()
    {
        $template = $this->_get_template_content();
        $s = '/(<perch:form[^>]*id="'.$this->formID.'"[^>]*>)/s';
        $count = preg_match($s, $template, $match);
        if ($count) {
            return new PerchXMLTag($match[0]);
        }
    }

    public function clear_from_post_env()
    {
        if (PerchUtil::count($this->data)) {
            foreach($this->data as $key=>$val) {
                if (isset($_POST[$key])) {
                    unset($_POST[$key]);
                }
            }
        }
    }

    public function duplicate($copy_vars, $unset_vars)
    {
        $new_vars = array();

        if (PerchUtil::count($this->data)) {
            foreach($this->data as $key=>$val) {
                if (in_array($key, $copy_vars)) {
                    $new_vars[$key] = $val;
                }
                if (in_array($key, $unset_vars)) {
                    unset($this->data[$key]);
                }
            }
        }

        $API = new PerchAPI(1.0, $this->app_id);
        $NewForm = $API->get('SubmittedForm');

        $NewForm->populate($this->formID, $this->templatePath, $new_vars, $this->files, $this->timestamp);

        return $NewForm;
    }

    public function redispatch($appID)
    {
        $this->redispatched = true;
        call_user_func($appID.'_form_handler', $this);
    }

    private function _get_template_content()
    {
        if ($this->templateContent === false) {
            $file = PerchUtil::file_path(PERCH_PATH.$this->templatePath);
            if (file_exists($file)) {
                $content = file_get_contents($file);
                // parse subtemplates
                $Template = new PerchTemplate();
                $this->templateContent = $Template->load($content, true); 
            }else{
                PerchUtil::debug('Template file not found: '.$file, 'error');
            }    
        }

        return $this->templateContent;
    }

    private function _get_mime_type($file)
    {
        $mimetype = false;

        $use_finfo_class        = true;
        $use_finfo_function     = true;
        $use_getimagesize       = true;
        $use_mime_content_type  = true;

        if ($use_finfo_class && class_exists('finfo')) {
            $finfo  = new finfo(FILEINFO_MIME, null);
            $result = $finfo->file($file);

            if ($result && strpos($result, ';')) {
                $parts = explode(';', $result);
                $mimetype = $parts[0];
            }
        }
        if ($use_finfo_function && function_exists('finfo_open')) {
            $finfo  = finfo_open(FILEINFO_MIME, null);
            $result = finfo_file($finfo, $file);
            finfo_close($finfo);

            if ($result && strpos($result, ';')) {
                $parts = explode(';', $result);
                $mimetype = $parts[0];
            }
        }

        if ($mimetype==false && $use_getimagesize && function_exists('getimagesize')) {
            $result = getimagesize($file);
            if (is_array($result)) $mimetype = $result['mime'];
        }

        if ($mimetype==false && $use_mime_content_type && function_exists('mime_content_type')) {
            $mimetype = mime_content_type($file);
        }

        return $mimetype;
    }

    private function _parse_filetypes_file()
    {
        $file = PERCH_PATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'filetypes.ini';
        if (!file_exists($file)) {
            PerchUtil::debug('Missing filetypes.ini file!', 'error');
            return array();
        }

        $out = array();
        $contents = file_get_contents($file);
        if ($contents) {
            $lines = explode("\n", $contents);
            $key = 'undefined';
            foreach($lines as $line) {
                if (trim($line)=='') continue;

                if (strpos($line, '[')!==false) {
                    $key =  str_replace(array('[', ']'), '', trim($line));
                    continue;
                }

                if ($key) $out[$key][] = trim($line);
            }
        }

        return $out;
    }
}
