<?php

class PerchUser extends PerchBase
{
    protected $table  = 'users';
    protected $pk     = 'userID';

    private $password_token_lifetime_secs = 14400; // 4 hours

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

    public function send_password_recovery_link()
    {
        $token = $this->create_recovery_token();

        $Email = new PerchEmail('password-recover.html');

        $Email->recipientEmail($this->userEmail());
        $Email->senderName(PERCH_EMAIL_FROM_NAME);
        $Email->senderEmail(PERCH_EMAIL_FROM);

        $Email->set('username', $this->userUsername());
        $Email->set('givenname', $this->userGivenName());
        $Email->set('familyname', $this->userFamilyName());
        $Email->set('sendername', PERCH_EMAIL_FROM_NAME);
        $Email->set('url', 'http://' . $_SERVER['HTTP_HOST'] . PERCH_LOGINPATH.'/core/reset/?token='.$token);

        return $Email->send();
    }

    public function set_new_password($new_password)
    {
        // check which type of password - default is portable
        if (defined('PERCH_NONPORTABLE_HASHES') && PERCH_NONPORTABLE_HASHES) {
            $portable_hashes = false;
        }else{
            $portable_hashes = true;
        }

        $Hasher = new PasswordHash(8, $portable_hashes);

        $data['userPassword'] = $Hasher->HashPassword($new_password);
        $data['userPasswordTokenExpires'] = date('Y-m-d H:i:s');

        $this->update($data);

        return true;
    }


    public function reset_pwd_and_notify()
    {
        $new_password  = PerchUser::generate_password();

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


    public static function generate_password($length=8, $include_uppercase=true)
    {
        $vals = '';
        if ($include_uppercase) {
            $vals .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $vals .= 'abcdefghijklmnopqrstuvwxyz0123456789';
        $pwd = '';
        for ($i=0; $i<$length; $i++) {
            $pwd .= $vals[rand(0, strlen($vals)-1)];
        }
        return $pwd;
    }


    private function create_recovery_token()
    {
        $data = array();

        if (strtotime($this->userPasswordTokenExpires()) < time()) {
            $data['userPasswordToken'] = $this->generate_password(32, false);
        }

        $data['userPasswordTokenExpires'] = date('Y-m-d H:i:s', time()+$this->password_token_lifetime_secs);

        $this->update($data);

        return $this->userPasswordToken();
    }
}
