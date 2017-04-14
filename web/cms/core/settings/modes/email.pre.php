<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');
    

    $Form   = $API->get('Form');
    $Email  = new PerchEmail('settings-test.html');

    $req = array();
    $req['email']      = "Required";

    $Form->set_required($req);
    
    if ($Form->posted() && $Form->validate()) {

        $data     = [];
        $postvars = ['email'];
        $data     = $Form->receive($postvars);

		if (PERCH_DEBUG) {
            $Email->SMTPDebug = 2;
        }
        
        $Email->recipientEmail($data['email']);
        $Email->senderName(PERCH_EMAIL_FROM_NAME);
        $Email->senderEmail(PERCH_EMAIL_FROM);

        if ($Email->send()) {
            $Alert->set('success', PerchLang::get('The email has been successfully sent.'));
        }else{
            $Alert->set('alert', $Email->errors);
        }
    }