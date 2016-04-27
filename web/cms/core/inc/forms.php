<?php
    function perch_find_posted_forms()
    {
        if (isset($_POST['cms-form']) && $_POST['cms-form']!='') {
            $Perch = Perch::fetch();
            $post  = $_POST;
            $key   = $post['cms-form'];
            unset($post['cms-form']);
            $Perch->dispatch_form($key, $post, $_FILES);
        }
        if (isset($_GET['cms-form']) && $_GET['cms-form']!='') {
            $Perch = Perch::fetch();
            $post  = $_GET;
            $key   = $post['cms-form'];
            unset($post['cms-form']);
            $Perch->dispatch_form($key, $post, $_FILES);
        }
    }

    if (PERCH_RUNWAY_ROUTED) {
        $Perch = Perch::fetch();
        $Perch->on('page.loaded', function(){
            perch_find_posted_forms();
        });
    }else{
        perch_find_posted_forms();
    }