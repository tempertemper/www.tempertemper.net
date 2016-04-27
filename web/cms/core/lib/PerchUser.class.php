<?php

class PerchUser extends PerchBase
{
    protected $table  = 'users';
    protected $pk     = 'userID';

    private $password_token_lifetime_secs = 14400; // 4 hours

    public $msg;

    public function send_welcome_email($token_mode=false)
    {
        $sender_name = PERCH_EMAIL_FROM_NAME;

        if ($token_mode) {
            $Email = new PerchEmail('user-welcome-token.html');
        }else{
            $Email = new PerchEmail('user-welcome.html');
        }

        $Email->recipientEmail($this->userEmail());
        $Email->senderName($sender_name);
        $Email->senderEmail(PERCH_EMAIL_FROM);

        $Email->set('username', $this->userUsername());
        
        $Email->set('givenname', $this->userGivenName());
        $Email->set('familyname', $this->userFamilyName());
        $Email->set('sendername', $sender_name);
        $Email->set('url', PerchUtil::url_to_ssl_if_needed('http://' . $_SERVER['HTTP_HOST'] . PERCH_LOGINPATH));

        if ($token_mode) {
            $token = $this->create_recovery_token();
            $Email->set('url', PerchUtil::url_to_ssl_if_needed('http://' . $_SERVER['HTTP_HOST'] . PERCH_LOGINPATH.'/core/reset/?token='.$token.'&new=1'));
        }else{
            $Email->set('password', $this->clear_pwd());
        }

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
        $Email->set('url', PerchUtil::url_to_ssl_if_needed('http://' . $_SERVER['HTTP_HOST'] . PERCH_LOGINPATH.'/core/reset/?token='.$token));

        return $Email->send();
    }

    public function send_lockout_email()
    {
        $token = $this->create_recovery_token();

        $Email = new PerchEmail('account-lockout.html');

        $Email->recipientEmail($this->userEmail());
        $Email->senderName(PERCH_EMAIL_FROM_NAME);
        $Email->senderEmail(PERCH_EMAIL_FROM);

        $Email->set('username', $this->userUsername());
        $Email->set('givenname', $this->userGivenName());
        $Email->set('familyname', $this->userFamilyName());
        $Email->set('sendername', PERCH_EMAIL_FROM_NAME);
        $Email->set('url', PerchUtil::url_to_ssl_if_needed('http://' . $_SERVER['HTTP_HOST'] . PERCH_LOGINPATH.'/core/reset/?token='.$token));

        return $Email->send();
    }

    public function set_new_password($new_password)
    {
        // copy old password into password history table
        $data = array(
            'userID' => $this->id(),
            'userPassword' => $this->userPassword(),
            'passwordLastUsed' => date('Y-m-d H:i:s'),
            );
        $this->db->insert(PERCH_DB_PREFIX.'user_passwords', $data);


        $Hasher = PerchUtil::get_password_hasher();

        $data = array();
        $data['userPassword'] = $Hasher->HashPassword($new_password);
        $data['userPasswordTokenExpires'] = date('Y-m-d H:i:s');
        $data['userLastFailedLogin'] = null;
        $data['userFailedLoginAttempts'] = 0;

        $this->update($data);

        return true;
    }

    public function validate_password($clear_pwd)
    {
        $Hasher = PerchUtil::get_password_hasher();
        return $Hasher->CheckPassword($clear_pwd, $this->userPassword());
    }

    public function reset_pwd_and_notify()
    {
        $new_password  = PerchUser::generate_password();

        $data   = array();

        $Hasher = PerchUtil::get_password_hasher();

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

    public function password_meets_requirements($clear_pwd)
    {
        if (defined('PERCH_STRONG_PASSWORDS') && PERCH_STRONG_PASSWORDS) {

            $pwd_min_len = 6;

            if (defined('PERCH_PASSWORD_MIN_LENGTH')) {
                $pwd_min_len = (int)PERCH_PASSWORD_MIN_LENGTH;
            }

            $user = $this->userUsername();
            $pass = $clear_pwd;

            // http://docstore.mik.ua/orelly/webprog/pcook/ch14_06.htm

            $lc_pass = strtolower($pass);
    
            // check password with numbers or punctuation subbed for letters
            $denum_pass = strtr($lc_pass,'5301!$@','seollsa');
            $lc_user    = strtolower($user);

            // the password must be at least $pwd_min_len characters
            if (strlen($pass) < $pwd_min_len) {
                $this->msg = 'That password is too short. Make it longer.';
                return false;
            }

            // the password can't be the username (or reversed username) 
            if (($lc_pass == $lc_user) || ($lc_pass == strrev($lc_user)) ||
                ($denum_pass == $lc_user) || ($denum_pass == strrev($lc_user))) {
                $this->msg = 'That password is based on your username. Choose something different.';
                return false;
            }

            // count how many lowercase, uppercase, and digits are in the password 
            $uc = 0; $lc = 0; $num = 0; $other = 0;
            for ($i = 0, $j = strlen($pass); $i < $j; $i++) {
                $c = substr($pass,$i,1);
                if (preg_match('/^[[:upper:]]$/',$c)) {
                    $uc++;
                } elseif (preg_match('/^[[:lower:]]$/',$c)) {
                    $lc++;
                } elseif (preg_match('/^[[:digit:]]$/',$c)) {
                    $num++;
                } else {
                    $other++;
                }
            }

            // the password must have more than two characters of at least 
            // two different kinds 
            $max = $j - 2;
            if ($uc > $max) {
                $this->msg = "That password has too many upper case characters. Mix it up a bit.";
                return false;
            }
            if ($lc > $max) {
                $this->msg = "That password has too many lower case characters. Mix it up a bit.";
                return false;
            }
            if ($num > $max) {
                $this->msg = "That password has too many numeral characters. Mix it up a bit.";
                return false;
            }
            if ($other > $max) {
                $this->msg = "That password has too many special characters. Mix it up a bit.";
                return false;
            }

            // only check for existing users (pwd change) not new users (pwd create)
            if ($this->id()) {


                // has it been used in the last 6 months?
                $backdate = strtotime('-6 MONTHS');
                $sql = 'SELECT userPassword FROM '.PERCH_DB_PREFIX.'user_passwords
                        WHERE userID='.$this->db->pdb((int)$this->id()).' AND passwordLastUsed > '.$this->db->pdb(date('Y-m-d H:i:s', $backdate));
                $old_passwords = $this->db->get_rows_flat($sql);
                // include the current password
                $old_passwords[] = $this->userPassword();

                if (PerchUtil::count($old_passwords)) {

                    $Hasher = PerchUtil::get_password_hasher();

                    foreach($old_passwords as $old_pwd) {
                        if ($Hasher->CheckPassword($pass, $old_pwd)) {
                            $this->msg = "That password has been used before. Choose a new password.";
                            return false;
                        }
                    }
                }
            }
            

        }

        // strong pwds not enabled, so there are no special requirements.
        // or has passed all the above checks
        return true;
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
