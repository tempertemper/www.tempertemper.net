<?php

class PerchUser extends PerchBase
{
    protected $table  = 'users';
    protected $pk     = 'userID';
    
    
    
    public function send_welcome_email()
    {
        $sender_name = PERCH_EMAIL_FROM_NAME;
        
        $Email = new PerchEmail('user-welcome.html');
        
        $Email->recipientEmail($this->userEmail());
        $Email->senderName($sender_name);
        $Email->senderEmail(PERCH_EMAIL_FROM);

        $Email->set('username', $this->userUsername());
        $Email->set('password', $this->clear_pwd());
        $Email->set('givenname', $this->userGivenName());
        $Email->set('familyname', $this->userFamilyName());
        $Email->set('sendername', $sender_name);
        $Email->set('url', 'http://' . $_SERVER['HTTP_HOST'] . PERCH_LOGINPATH);
        
        $Email->send();
    }
    
    
    public function reset_pwd_and_notify()
    {
        $new_password  = $this->generate_password();
        
        $data   = array();

        // check which type of password - default is portable
        if (defined('PERCH_NONPORTABLE_HASHES') && PERCH_NONPORTABLE_HASHES) {
            $portable_hashes = false;
        }else{
            $portable_hashes = true;
        }
        
        $Hasher = new PasswordHash(8, $portable_hashes);
        
        $data['userPassword'] = $Hasher->HashPassword($new_password);
        
        $this->update($data);
        
        $Email = new PerchEmail('password-reset.html');
        //$Email->subject('Your CMS password has been reset');
        
        $Email->recipientEmail($this->userEmail());
        $Email->senderName(PERCH_EMAIL_FROM_NAME);
        $Email->senderEmail(PERCH_EMAIL_FROM);
        
        $Email->set('username', $this->userUsername());
        $Email->set('password', $new_password);
        $Email->set('givenname', $this->userGivenName());
        $Email->set('familyname', $this->userFamilyName());
        $Email->set('sendername', PERCH_EMAIL_FROM_NAME);
        $Email->set('url', 'http://' . $_SERVER['HTTP_HOST'] . PERCH_LOGINPATH);
        
        return $Email->send();

    }
    
    private function generate_password($length=8)
    {
        $vals = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $pwd = '';
        for ($i=0; $i<$length; $i++) {
            $pwd .= $vals[rand(0, strlen($vals)-1)];
        }
        return $pwd;
    }
}

?>