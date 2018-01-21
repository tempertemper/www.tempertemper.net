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

    private $bcc_list    = array();

    private $template_data;

    private $files = array();

    private $body = false;

    private $html = false;

    private $template_method = 'dollar';
    private $template_ns     = 'email';

    public $errors = '';

    function __construct($template, $namespace='email')
    {
        $this->template    = $template;
        $this->template_ns = $namespace;

        if ($template) {
            $this->set_template($template, $namespace);
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

    public function set_template($template, $namespace='email')
    {
        $this->template    = $template;
        $this->template_ns = $namespace;

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

        $user_file     = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/'.$template);
        $core_file     = PerchUtil::file_path(PERCH_CORE . '/emails/'.$template);

        if (file_exists($user_file)) {
            $this->template_path = $user_file;
        }elseif (file_exists($local_file)) {
            $this->template_path = $local_file;
        }else{
            $this->template_path = $core_file;
        }

        PerchUtil::debug('Using email template: '.$this->template_path.' ('.$type.')', 'template');

        // detect type
        if (file_exists($this->template_path)) {
            $template_contents = file_get_contents($this->template_path);
        }else{
            $template_contents = '';
        }
        

        if (strpos($template_contents, '<perch:')!==false) {
            $this->template_method('perch');
        }else{
            $this->template_method('dollar');
        }

    }

    public function body($str=null)
    {
        if ($str === null) {
            return $this->body;
        }

        $this->body = $str;
    }


    public function subject($str=null)
    {
        if ($str === null) {
            return $this->subject;
        }

        $this->subject = $str;
        $this->vars['email_subject'] = $str;
    }

    public function senderName($str=null)
    {
        if ($str === null) {
            return $this->senderName;
        }

        $this->senderName = $str;
    }

    public function senderEmail($str=null)
    {
        if ($str === null) {
            return $this->senderEmail;
        }

        $this->senderEmail = $str;
    }

    public function recipientEmail($str=null)
    {
        if ($str === null) {
            return $this->recipientEmail;
        }

        $this->recipientEmail = $str;
    }

    public function recipientName($str=null)
    {
        if ($str === null) {
            return $this->recipientName;
        }

        $this->recipientName = $str;
    }

    public function replyToEmail($str=null)
    {
        if ($str === null) {
            return $this->replyToEmail;
        }

        $this->replyToEmail = $str;
    }

    public function replyToName($str=null)
    {
        if ($str === null) {
            return $this->replyToName;
        }

        $this->replyToName = $str;
    }

    public function bccToEmail($str=null)
    {
        if ($str === null) {
            return $this->bcc_list;
        }

        $this->bcc_list[] = $str;
    }

    public function set($key, $str=null)
    {
        if ($str === null) {
            return (isset($this->vars[$key]) ? $this->vars[$key] : false);
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

    public function removeAttachedFiles()
    {
        $this->files = [];
    }

    public function send()
    {
        $LogMessage = new PerchSystemEventSubject;
        $LogMessage->recipients = array();
        $LogMessage->attachments = array();

        $body = $this->build_message();
        $LogMessage->body = $body;

        $debug_recipients = array();

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $LogMessage->charset = 'utf-8';

        if ($this->html) {
            $mail->IsHTML();
            $mail->AltBody = $this->plain_textify($body);

            $LogMessage->is_html = true;
            $LogMessage->altbody = $mail->AltBody;
        }else{
            $LogMessage->is_html = false;
        }

        try {
            if ($this->replyToEmail()) {
                $mail->AddReplyTo($this->replyToEmail(), $this->replyToName());
                $LogMessage->reply_to = array('email' => $this->replyToEmail(), 'name' => $this->replyToName());
            }

            if (PerchUtil::count($this->bcc_list)) {
                foreach($this->bcc_list as $bcc) {
                    $mail->addBCC($bcc);
                }
                $LogMessage->bcc = $this->bcc_list;
            }

            $mail->SetFrom($this->senderEmail(), $this->senderName());

            $LogMessage->from = array('email' => $this->senderEmail(), 'name' => $this->senderName());

            if (is_array($this->recipientEmail)) {
                foreach($this->recipientEmail as $recipient) {
                    $mail->AddAddress($recipient);
                    $debug_recipients[] = $recipient;
                    $LogMessage->recipients[] = $recipient;
                }
            }else{
               $mail->AddAddress($this->recipientEmail(), $this->recipientName());
               $debug_recipients[] = $this->recipientEmail();
               $LogMessage->recipients[] = $this->recipientEmail();
            }

            $mail->Subject = $this->subject();
            $LogMessage->subject = $this->subject();

            $mail->Body = $body;

            if (PerchUtil::count($this->files)) {
                foreach($this->files as $file) {
                    $mail->AddAttachment($file['path'], $file['name']); // attachment
                    $LogMessage->attachments[] = $file;
                }
            }

            switch(strtolower(PERCH_EMAIL_METHOD)) {
                case 'sendmail':
                    $mail->IsSendmail();
                    $LogMessage->sent_by = 'sendmail';
                    break;

                case 'smtp':

                    $mail->IsSMTP();
                    $mail->Host       = PERCH_EMAIL_HOST;
                    $mail->SMTPAuth   = PERCH_EMAIL_AUTH;
                    $mail->Port       = PERCH_EMAIL_PORT;
                    $mail->Username   = PERCH_EMAIL_USERNAME;
                    $mail->Password   = PERCH_EMAIL_PASSWORD;
                    $mail->SMTPSecure = PERCH_EMAIL_SECURE;

                    $LogMessage->sent_by = 'smtp';

                    $LogMessage->smtp_host     = PERCH_EMAIL_HOST;
                    $LogMessage->smtp_auth     = PERCH_EMAIL_AUTH;
                    $LogMessage->smtp_port     = PERCH_EMAIL_PORT;
                    $LogMessage->smtp_username = PERCH_EMAIL_USERNAME;
                    $LogMessage->smtp_password = PERCH_EMAIL_PASSWORD;
                    $LogMessage->smtp_secure   = PERCH_EMAIL_SECURE;

                    break;
            }

            if (!$mail->Send()) {
                PerchUtil::debug($mail->ErrorInfo, 'error');
                return false;
            }else{
                PerchUtil::debug('Sent email: "'.$this->subject().'" to '.implode(', ', $debug_recipients), 'success');

                $Perch = Perch::fetch();
                $Perch->event('email.send', $LogMessage);
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
            PerchUtil::debug('Building message with Perch template');
            return $this->build_message_with_perch();
        }

        PerchUtil::debug('Building message with Dollar template');
        return $this->build_message_with_dollar();
    }

    private function build_message_with_perch()
    {
        if (isset($this->app_id)) {
            $API = new PerchAPI($this->version, $this->app_id);
            $Template = $API->get('Template');
            $Template->set($this->template, $this->template_ns);
        }else{
            $Template = new PerchTemplate($this->template, 'email');    
        }
        
        $html = $Template->render_group(array($this->vars), true);
        $html = $Template->apply_runtime_post_processing($html);
        $this->subject($this->find_subject_from_html($html));
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
                $this->subject($this->find_subject_from_html($contents));
            }

            return stripslashes($contents);
        }else{
            PerchUtil::debug('Template does not exist: '. $template, 'error');
            return false;
        }
    }

    private function find_subject_from_html($contents)
    {
        $s = '/<title>([\w\W]*?)<\/title>/';
        if (preg_match($s, $contents, $matches)) {
            if (isset($matches[1])) {
                return trim($matches[1]);
            }
        }
        return false;
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