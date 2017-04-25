<?php

	function perch_encode($str, $quotes = false) 
	{
		return PerchUtil::html($str, $quotes);
	}

	function perch_get($var, $default = false)
    {
        if (isset($_GET[$var]) && $_GET[$var]!='') {
            return rawurldecode($_GET[$var]);
        }

        if (PERCH_RUNWAY) {
            $r = PerchSystem::get_url_var($var);
            if ($r) return $r;
        }

        return $default;
    }

    function perch_post($var, $default = false)
    {
        if (isset($_POST[$var]) && $_POST[$var]!='') {
            return $_POST[$var];
        }

        return $default;
    }

    function perch_layout($file, $vars = array(), $return = false)
    {
        $Perch = Perch::fetch();
        $Perch->set_layout_vars($vars);

        if ($return) {
            flush();
            ob_start();
        }

        $path = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/layouts/'.$file.'.php');

        if (file_exists($path)) {
            $Perch->layout_depth++;
            include($path);
            $Perch->layout_depth--;
        }else{
            echo '<!-- Missing layout file: "'.PerchUtil::html('templates/layouts/'.$file.'.php').'" -->';
            PerchUtil::debug('Missing layout file: '.$path, 'error');
        }

        if ($return) {
            return ob_get_clean();
        }
        PerchUtil::flush_output();
    }

    function perch_layout_var($var, $return = false)
    {
        $Perch = Perch::fetch();
        $var = $Perch->get_layout_var($var);

        if ($return) return $var;

        echo PerchUtil::html($var);
    }

    function perch_layout_has($var)
    {
        $Perch = Perch::fetch();
        $var = $Perch->get_layout_var($var);
        if ($var) return true;
        return false;
    }

    function perch_template($tpl, $vars = array(), $return = false)
    {
        $Template = new PerchTemplate($tpl);

        if (!is_array($vars)) {
            PerchUtil::debug('Non-array content value passed to perch_template.', 'error');
            $vars = array();
        }

        if (count($vars)==0) {
            $Template->use_noresults();
        }

        if (!PerchUtil::is_assoc($vars)) {
            $html = $Template->render_group($vars, true);
        }else{
            $html = $Template->render($vars);
        }

        $html     = $Template->apply_runtime_post_processing($html);

        if ($return) return $html;
        echo $html;
        PerchUtil::flush_output();
    }

    function perch_page_url($opts = array(), $return = false)
    {
        $default_opts = array(
            'hide-extensions'    => false,
            'hide-default-doc'   => true,
            'add-trailing-slash' => false,
            'include-domain'     => true,
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $Pages = new PerchContent_Pages;

        $r = $Pages->get_full_url($opts);

        if ($return) return $r;

        echo PerchUtil::html($r);
    }