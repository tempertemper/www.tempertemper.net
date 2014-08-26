<?php

    require('PerchForms_Forms.class.php');
    require('PerchForms_Form.class.php');
    require('PerchForms_Responses.class.php');
    require('PerchForms_Response.class.php');
    require('PerchForms_Akismet.class.php');

    function perch_forms_form_handler($SubmittedForm)
    {
        if ($SubmittedForm->validate()) {
            $API  = new PerchAPI(1.0, 'perch_forms');
            $Forms = new PerchForms_Forms($API);
        
            $formKey = $SubmittedForm->id;
        
            $Form = $Forms->find_by_key($formKey);
        
            if (!is_object($Form)) {
                $data = array();
                $data['formKey'] = $formKey;
                $data['formTemplate'] = $SubmittedForm->templatePath;
                $data['formOptions'] = PerchUtil::json_safe_encode(array('store'=>true));
                $attrs   = $SubmittedForm->get_form_attributes();
                if ($attrs->label()) {
                    $data['formTitle'] = $attrs->label();
                }else{
                    $data['formTitle'] = PerchUtil::filename($formKey, false);
                }
                $Form = $Forms->create($data);
            }
        
            if (is_object($Form)) {
                $Form->process_response($SubmittedForm);
            }
        }
        $Perch = Perch::fetch();
        PerchUtil::debug($Perch->get_form_errors($SubmittedForm->formID));
        
    }
    
    
    function perch_form($template, $return=false)
    {
        $API  = new PerchAPI(1.0, 'perch_forms');
        $Template = $API->get('Template');
        $Template->set('forms'.DIRECTORY_SEPARATOR.$template, 'forms');
        $html = $Template->render(array());
        $html = $Template->apply_runtime_post_processing($html);
        
        if ($return) return $html;
        echo $html;
    }

?>