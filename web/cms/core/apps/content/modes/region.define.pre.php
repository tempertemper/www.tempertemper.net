<?php

    $API    = new PerchAPI(1.0, 'core');
    $Lang   = $API->get('Lang');
    $HTML   = $API->get('HTML');

    /* --------- Template Form ----------- */

    $fTemplate = new PerchForm('template');

    $req = array();
    $req['regionTemplate'] = "Required";
    $fTemplate->set_required($req);

    if ($fTemplate->posted() && $fTemplate->validate()) {
        $postvars = array('regionTemplate', 'regionMultiple');
        $data = $fTemplate->receive($postvars);

        if (!isset($data['regionMultiple'])) {
            $data['regionMultiple'] = 0;
        }

        $data['regionNew'] = 0;

        $Region->update($data);

        if ($Settings->get('content_singlePageEdit')->val()=='1') {
            $Region->set_option('edit_mode', 'singlepage');
        }else{
            $Region->set_option('edit_mode', 'listdetail');
        }
    }
