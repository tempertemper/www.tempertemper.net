<?php

class PerchEmail
{

    private $vars = array();
    private $template = false;
    private $template_path;

    private $cache	= array();

    private $subject;

    private $senderName;
    private $senderEmail;

    private $recipientEmail;
    private $recipientName = '';

    private $replyToEmail  = '';
    private $replyToName   = '';

    private $template_data;

    private $files = array();

    private $body = false;

    private $html = false;

    private $template_method = 'dollar';

    public $errors = '';

    function __construct($template)
    {
        $this->template = $template;

        if ($template) {
            $this->set_template($template);
        }

        $this->set('http_host', $_SERVER['HTTP_HOST']);

        if (!defined('PERCH_EMAIL_METHOD')) define('PERCH_EMAIL_METHOD', 'mail');


        if (!defined('PERCH_EMAIL_HOST'))       define('PERCH_EMAIL_HOST', 'localhost');
        if (!defined('PERCH_EMAIL_AUTH'))       define('PERCH_EMAIL_AUTH', false);
        if (!defined('PERCH_EMAIL_PORT'))       define('PERCH_EMAIL_PORT', 25);
        if (!defined('PERCH_EMAIL_SECURE'))     define('PERCH_EMAIL_SECURE', '');
        if (!defined('PERCH_EMAIL_USERNAME'))   define('PERCH_EMAIL_USERNAME', "not configured");
        if (!defined('PERCH_EMAIL_PASSWORD'))   define('PERCH_EMAIL_PASSWORD', "not configured");
    }

    public function set_template($template)
    {
        $this->template = $template;

        $type = PerchUtil::file_extension($template);

        if (!$type) {
            $type = 'txt';
            $template .= '.txt';
            $this->html = false;
        }else{
            if ($type == 'html') {
                $this->html = true;
            }
        }

        if (isset($this->app_id)) {
            $local_file = PerchUtil::file_path(PERCH_PATH.'/addons/apps/'.$this->app_id.'/templates/'.$template);
        }else{
            $local_file = false;
        }

        $user_file  = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/'.$template);
        $core_file  = PerchUtil::file_path(PERCH_CORE . '/emails/'.$template);

        if (file_exists($user_file)) {
            $this->template_path = $user_file;
        }elseif (file_exists($local_file)) {
            $this->template_path = $local_file;
        }else{
            $this->template_path = $core_file;
        }

        PerchUtil::debug('Using email template: '.$this->template_path.' ('.$type.')', 'template');

    }

    public function body($str=false)
    {
        if ($str === false) {
            return $this->body;
        }

        $this->body = $str;
    }


    public function subject($str=false)
    {
        if ($str === false) {
            return $this->subject;
        }

        $this->subject = $str;
        $this->vars['email_subject'] = $str;
    }

    public function senderName($str=false)
    {
        if ($str === false) {
            return $this->senderName;
        }

        $this->senderName = $str;
    }

    public function senderEmail($str=false)
    {
        if ($str === false) {
            return $this->senderEmail;
        }

        $this->senderEmail = $str;
    }

    public function recipientEmail($str=false)
    {
        if ($str === false) {
            return $this->recipientEmail;
        }

        $this->recipientEmail = $str;
    }

    public function recipientName($str=false)
    {
        if ($str === false) {
            return $this->recipientName;
        }

        $this->recipientName = $str;
    }

    public function replyToEmail($str=false)
    {
        if ($str === false) {
            return $this->replyToEmail;
        }

        $this->replyToEmail = $str;
    }

    public function replyToName($str=false)
    {
        if ($str === false) {
            return $this->replyToName;
        }

        $this->replyToName = $str;
    }

    public function set($key, $str=false)
    {
        if ($str === false) {
            return $this->vars[$key];
        }

        $this->vars[$key] = $str;
    }

    public function set_bulk($data)
    {
        if (is_array($data)) {

            foreach ($data as $key=>$val) {
                $this->set($key, $val);
            }

        }
    }

    public function template_method($method=false)
    {
        if ($method) {
            $this->template_method = $method;
        }

        return $this->template_method;
    }

    public function attachFile($name, $path, $mimetype)
    {
        $file = array();
        $file['name'] = $name;
        $file['path'] = $path;
        $file['mimetype'] = $mimetype;
        $this->files[] = $file;
    }

    public function send()
    {
        $body = $this->build_message();

        $debug_recipients = array();

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        if ($this->html) {
            $mail->IsHTML();
            $mail->AltBody = $this->plain_textify($body);
        }

        try {
            if ($this->replyToEmail()) {
                $mail->AddReplyTo($this->replyToEmail(), $this->replyToName());
            }

            $mail->SetFrom($this->senderEmail(), $this->senderName());

            if (is_array($this->recipientEmail)) {
                foreach($this->recipientEmail as $recipient) {
                    $mail->AddAddress($recipient);
                    $debug_recipients[] = $recipient;
                }
            }else{
               $mail->AddAddress($this->recipientEmail(), $this->recipientName());
               $debug_recipients[] = $this->recipientEmail();
            }

            $mail->Subject = $this->subject();

            $mail->Body = $body;

            if (PerchUtil::count($this->files)) {
                foreach($this->files as $file) {
                    $mail->AddAttachment($file['path'], $file['name']); // attachment
                }
            }

            switch(strtolower(PERCH_EMAIL_METHOD)) {
                case 'sendmail':
                    $mail->IsSendmail();
                    break;

                case 'smtp':

                    $mail->IsSMTP();
                    $mail->Host       = PERCH_EMAIL_HOST;
                    $mail->SMTPAuth   = PERCH_EMAIL_AUTH;
                    $mail->Port       = PERCH_EMAIL_PORT;
                    $mail->Username   = PERCH_EMAIL_USERNAME;
                    $mail->Password   = PERCH_EMAIL_PASSWORD;
                    $mail->SMTPSecure = PERCH_EMAIL_SECURE;


                    break;
            }

            if (!$mail->Send()) {
                PerchUtil::debug($mail->ErrorInfo, 'error');
                return false;
            }else{
                PerchUtil::debug('Sent email: "'.$this->subject().'" to '.implode(', ', $debug_recipients), 'success');
                return true;
            }

        }catch (phpmailerException $e) {
            $this->errors .= $e->errorMessage();
        }catch (Exception $e) {
            $this->errors .= $e->getMessage();
        }

        PerchUtil::debug($this->errors, 'error');

        return false;


    }


    private function build_message()
    {
        if ($this->template_method=='perch') {
            return $this->build_message_with_perch();
        }

        return $this->build_message_with_dollar();
    }

    private function build_message_with_perch()
    {
        $Template = new PerchTemplate($this->template, 'email');
        $html = $Template->render($this->vars);
        return $html;
    }

    private function build_message_with_dollar()
    {
        $path       = $this->template_path;
        $template   = $this->template;
        $data       = $this->vars;

        if (!$template) {
            return $this->body;
        }


        // test for data
        if (!is_array($data)){
            PerchUtil::debug('No data sent to email templating engine.', 'notice');
            return false;
        }


        // check if template is cached
        if (isset($this->cache[$template])){
            // use cached copy
            $contents   = $this->cache[$template];
        }else{
            // read and cache
            if (file_exists($path)){
                $contents   = file_get_contents($path);
                $this->cache[$template] = addslashes($contents);
            }
        }

        if (isset($contents)){
            $this->template_data    = $data;
            $contents               = preg_replace_callback('/\$(\w+)/', array($this, "substitute_vars"), $contents);
            $this->template_data    = '';

            if ($this->html) {
                $s = '/<title>(.*?)<\/title>/';
                if (preg_match($s, $contents, $matches)) {
                    if (isset($matches[1])) {
                        $this->subject($matches[1]);
                    }
                }
            }

            return stripslashes($contents);
        }else{
            PerchUtil::debug('Template does not exist: '. $template, 'error');
            return false;
        }
    }

    private function substitute_vars($matches)
    {
    	$tmp_template_data = $this->template_data;
    	if (isset($tmp_template_data[$matches[1]])){
    		return $tmp_template_data[$matches[1]];
    	}else{
    		PerchUtil::debug('Template variable not found: '.$matches[1], 'notice');
    		return '';
    	}
    }

    private function plain_textify($html)
    {
        $out = preg_replace('#<style([\W\w]*)?</style>#', '', $html);
        $out = preg_replace('#[\r\n](\s{2,})#', "\n\t", $out);
        $out = strip_tags($out);

        return $out;
    }


}