<?php

class PerchForms_Response extends PerchAPI_Base
{
    protected $table  = 'forms_responses';
    protected $pk     = 'responseID';
    
    public function fields()
    {
        $all = PerchUtil::json_safe_decode($this->responseJSON());
        if (isset($all->fields)) return $all->fields;
        return false;
    }
    
    public function page()
    {
        $all = PerchUtil::json_safe_decode($this->responseJSON());
        if (isset($all->page)) return $all->page;
        return false;
    }

    public function files()
    {
        $all = PerchUtil::json_safe_decode($this->responseJSON());
        if (isset($all->files)) return $all->files;
        return false;
    }
    
    public function mark_not_spam()
    {
        $data = array();
        $data['responseSpam'] = '0';
        $this->update($data);
        
        $json = PerchUtil::json_safe_decode($this->responseSpamData(),true);
        if (PerchUtil::count($json)) {
            $API = new PerchAPI(1, 'perch_forms');
            $Forms = new PerchForms_Forms($API);
            $Form = $Forms->find($this->formID());
            
            if ($Form) {
                $opts = $Form->get_settings();
                if (isset($opts['akismet']) && $opts['akismet']) {
                    if (isset($opts['akismetAPIKey']) && $opts['akismetAPIKey']!='') {
                        PerchForms_Akismet::submit_ham($opts['akismetAPIKey'], $json['fields'], $json['environment']);
                    }
                }
            }
        }
    }
    
    public function mark_as_spam()
    {
        $data = array();
        $data['responseSpam'] = '1';
        $this->update($data);
        
        $json = PerchUtil::json_safe_decode($this->responseSpamData(),true);
        if (PerchUtil::count($json)) {
            $API = new PerchAPI(1, 'perch_forms');
            $Forms = new PerchForms_Forms($API);
            $Form = $Forms->find($this->formID());
            
            if ($Form) {
                $opts = $Form->get_settings();
                if (isset($opts['akismet']) && $opts['akismet']) {
                    if (isset($opts['akismetAPIKey']) && $opts['akismetAPIKey']!='') {
                        PerchForms_Akismet::submit_spam($opts['akismetAPIKey'], $json['fields'], $json['environment']);
                    }
                }
            }
        }
    }
}
