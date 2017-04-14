<?php
    spl_autoload_register(function($class_name){
        if (strpos($class_name, 'PerchContent')===0) {
            include(PERCH_CORE.'/apps/content/'.$class_name.'.class.php');
            return true;
        }
        return false;
    });

    PerchSystem::register_search_handler('PerchContent_SearchHandler');

    if (PERCH_RUNWAY) {
        include(PERCH_CORE.'/runway/apps/content/runtime.php');
    }else{
        perch_content_check_preview();
    }

    function perch_content($key=null, $return=false, $custom_return=false)
    {
        if (is_array($return)) {
            // if perch_content() is used like perch_content_custom(), just make it work.
            return perch_content_custom($key, $return, $custom_return);
        }

        if ($key === null) {
            echo 'You must pass in a <em>name</em> for the content. e.g. <code style="color: navy;background: white;">&lt;' . '?php perch_content(\'Phone number\'); ?' . '&gt;</code>';
            return ' ';
        }

        $Content = PerchContent::fetch();
        $out = $Content->get($key);

        // Post processing - if there are still <perch:x /> tags
        if (strpos($out, '<perch:')!==false) {
            $Template   = new PerchTemplate();
            $out        = $Template->apply_runtime_post_processing($out);
        }

        if ($return) return $out;
        echo $out;
        PerchUtil::flush_output();
    }

    function perch_content_custom($key=null, $opts=array(), $return=false)
    {
        if ($key === null) return ' ';

        if (isset($opts['skip-template']) && $opts['skip-template']==true) {
            $return  = true;
            $postpro = false;

            if (isset($opts['return-html']) && $opts['return-html']) {
                $postpro = true;
            }
        }else{
            $postpro = true;
        }

        if (isset($opts['split-items']) && $opts['split-items']==true) {
            $return  = true;
        }

        if (isset($opts['pagination_var']))    $opts['pagination-var'] = $opts['pagination_var'];
        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $Content = PerchContent::fetch();
        $out     = $Content->get_custom($key, $opts);

        // Post processing - if there are still <perch:x /> tags
        if ($postpro) {
            if (is_array($out)) {

                // return-html
                if (isset($out['html'])) {
                    if (strpos($out['html'], '<perch:')!==false) {
                        $Template = new PerchTemplate();
                        $out['html'] = $Template->apply_runtime_post_processing($out['html']);
                    }
                }

                // split-items
                if (PerchUtil::count($out) && !isset($out['html'])) {
                    $Template = new PerchTemplate();
                    foreach($out as &$html_item) {
                        if (strpos($html_item, '<perch:')!==false) {
                            $html_item        = $Template->apply_runtime_post_processing($html_item);
                        }
                    }
                }
            }else{
                if (strpos($out, '<perch:')!==false) {
                    $Template = new PerchTemplate();
                    $out     = $Template->apply_runtime_post_processing($out);
                }
            }
        }

        if ($return) return $out;
        echo $out;
        PerchUtil::flush_output();
    }

    function perch_content_check_preview()
    {
        if (!defined('PERCH_PREVIEW_ARG')) define('PERCH_PREVIEW_ARG', 'preview');

        if (perch_get(PERCH_PREVIEW_ARG)) {

            $contentID   = 'all';
            $rev         = false;

            $var = perch_get(PERCH_PREVIEW_ARG);

            if ($var != 'all' && $var != 'preview') {
                $rev  = $var;
                if (strpos($rev, 'r')) {
                    $parts = explode('r', $rev);
                    $contentID = (int) $parts[0];
                    $rev       = (int) $parts[1];
                }
            }

            $Users       = new PerchUsers;
            $CurrentUser = $Users->get_current_user();

            if (is_object($CurrentUser) && $CurrentUser->logged_in()) {
                $Content = PerchContent::fetch();
                $Content->set_preview($contentID, $rev);
            }
        }
    }

    function perch_content_search($key=null, $opts=array(), $return=false)
    {
        if ($key!==null) {
            $key = trim(stripslashes($key));
        }

        $Content = PerchContent::fetch();

        $defaults = array();
        $defaults['template']           = 'search-result.html';
        $defaults['count']              = 10;
        $defaults['excerpt-chars']      = 250;
        $defaults['from-path']          = '/';
        $defaults['hide-extensions']    = false;
        $defaults['add-trailing-slash'] = false;
        $defaults['hide-default-doc']   = false;
        $defaults['no-conflict']        = false;
        $defaults['skip-template']        = false;
        $defaults['apps']               = array();

        if (count($opts)) {
            $opts = array_merge($defaults, $opts);
        }else{
            $opts = $defaults;
        }

        if (isset($opts['hide_extensions']))    $opts['hide-extensions'] = $opts['hide_extensions'];
        if (isset($opts['from_path']))          $opts['from-path']       = $opts['from_path'];
        if (isset($opts['excerpt_chars']))      $opts['excerpt-chars']   = $opts['excerpt_chars'];

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $out = $Content->search_content($key, $opts);

        if ($opts['skip-template']) return $out;

        // Post processing - if there are still <perch:x /> tags
        if (strpos($out, '<perch:')!==false) {
            $Template   = new PerchTemplate();
            $out        = $Template->apply_runtime_post_processing($out);
        }

        if ($return) return $out;
        echo $out;
        PerchUtil::flush_output();
    }

    function perch_search_form($opts=array(), $return=false)
    {
        $Perch = Perch::fetch();

        $defaults = array();
        $defaults['template'] = 'search-form.html';

        if (count($opts)) {
            $opts = array_merge($defaults, $opts);
        }else{
            $opts = $defaults;
        }

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $Template   = new PerchTemplate('search'.DIRECTORY_SEPARATOR.$opts['template']);
        $html = $Template->render(array());
        $html = $Template->apply_runtime_post_processing($html);

        if ($return) return $html;
        echo $html;
        PerchUtil::flush_output();
    }

    function perch_page_title($return=false)
    {
        if ($return) return perch_pages_title(true);

        perch_pages_title();
    }

    function perch_pages_title($return=false)
    {
        $attr_vars = PerchSystem::get_attr_vars();
        if (isset($attr_vars['pageTitle'])) {
            if ($return) return $attr_vars['pageTitle'];
            echo PerchUtil::html($attr_vars['pageTitle']);
            return;
        }

        $Page = PerchSystem::get_page_object();

        if (!$Page) {
            $Pages = new PerchContent_Pages;
            $Perch = Perch::fetch();
            $Page = $Pages->find_by_path($Perch->get_page());
            if ($Page instanceof PerchContent_Page) {
                PerchSystem::set_page_object($Page);
            }
        }

        $r = '';

        if (is_object($Page)) {
            $r = $Page->pageTitle();
        }

        if ($return) return $r;

        echo $r;
    }

    function perch_pages_navigation_text($return=false)
    {
        $attr_vars = PerchSystem::get_attr_vars();
        if (isset($attr_vars['pageNavText'])) {
            if ($return) return $attr_vars['pageNavText'];
            echo PerchUtil::html($attr_vars['pageNavText']);
            return;
        }

        $Page = PerchSystem::get_page_object();

        if (!$Page) {
            $Pages = new PerchContent_Pages;
            $Perch = Perch::fetch();
            $Page = $Pages->find_by_path($Perch->get_page());
            if ($Page instanceof PerchContent_Page) {
                PerchSystem::set_page_object($Page);
            }
        }

        $r = '';

        if (is_object($Page)) {
            $r = $Page->pageNavText();
        }

        if ($return) return $r;

        echo $r;
    }

    function perch_pages_next_page($opts=array(), $return=false)
    {
        $Pages = new PerchContent_Pages;
        $Perch = Perch::fetch();

        $default_opts = array(
            'hide-extensions'    => false,
            'hide-default-doc'   => true,
            'template'           => 'item.html',
            'skip-template'      => false,
            'add-trailing-slash' => false,
            'navgroup'           => false,
            'include-hidden'     => false,
            'use-attributes'     => true,
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        if ($opts['skip-template']) $return = true;

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $current_page = $Perch->get_page();

        $r = $Pages->get_sibling_navigation('next', $opts, $current_page);

        if ($return) return $r;

        echo $r;
    }

    function perch_pages_previous_page($opts=array(), $return=false)
    {
        $Pages = new PerchContent_Pages;
        $Perch = Perch::fetch();

        $default_opts = array(
            'hide-extensions'    => false,
            'hide-default-doc'   => true,
            'template'           => 'item.html',
            'skip-template'      => false,
            'add-trailing-slash' => false,
            'navgroup'           => false,
            'include-hidden'     => false,
            'use-attributes'     => true,
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        if ($opts['skip-template']) $return = true;

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $current_page = $Perch->get_page();

        $r = $Pages->get_sibling_navigation('prev', $opts, $current_page);

        if ($return) return $r;

        echo $r;
    }

    function perch_pages_parent_page($opts=array(), $return=false)
    {
        $Pages = new PerchContent_Pages;
        $Perch = Perch::fetch();

        $default_opts = array(
            'hide-extensions'    => false,
            'hide-default-doc'   => true,
            'template'           => 'item.html',
            'skip-template'      => false,
            'add-trailing-slash' => false,
            'navgroup'           => false,
            'include-hidden'     => false,
            'use-attributes'     => true,
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        if ($opts['skip-template']) $return = true;

        $current_page = $Perch->get_page();

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $r = $Pages->get_parent_navigation($opts, $current_page);

        if ($return) return $r;
        echo $r;
    }

    function perch_pages_navigation($opts=array(), $return=false)
    {
        $Pages = new PerchContent_Pages;
        $Perch = Perch::fetch();

        // translate renamed options from Perch v1
        if (isset($opts['from_path']))       $opts['from-path'] = $opts['from_path'];
        if (isset($opts['hide_extensions'])) $opts['hide-extensions'] = $opts['hide_extensions'];

        $default_opts = array(
            'from-path'            => '/',
            'levels'               => 0,
            'hide-extensions'      => false,
            'hide-default-doc'     => true,
            'flat'                 => false,
            'template'             => array('item.html'),
            'include-parent'       => false,
            'skip-template'        => false,
            'siblings'             => false,
            'only-expand-selected' => false,
            'add-trailing-slash'   => false,
            'navgroup'             => false,
            'access-tags'          => false,
            'include-hidden'       => false,
            'from-level'           => false,
            'use-attributes'       => true,
        );

        if (class_exists('PerchMembers_Session')) {
            $Session = PerchMembers_Session::fetch();

            if ($Session->logged_in) {
                $default_opts['access-tags'] = $Session->get_tags();
            }
        }


        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        if ($opts['skip-template']) $return = true;

        $current_page = $Perch->get_page();

        if ($opts['from-path']=='*') {
            $opts['from-path'] = $current_page;
        }

        $r = $Pages->get_navigation($opts, $current_page);

        if ($return) return $r;

        echo $r;
        PerchUtil::flush_output();
    }

    function perch_pages_breadcrumbs($opts=array(), $return=false)
    {
        $Pages = new PerchContent_Pages;
        $Perch = Perch::fetch();

        $default_opts = array(
            'hide-extensions'    => false,
            'hide-default-doc'   => true,
            'template'           => 'breadcrumbs.html',
            'skip-template'      => false,
            'add-trailing-slash' => false,
            'navgroup'           => false,
            'include-hidden'     => false,
            'use-attributes'     => true,
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        if ($opts['skip-template']) $return = true;

        $current_page = $Perch->get_page();

        $opts['from-path'] = $current_page;

        $r = $Pages->get_breadcrumbs($opts);

        if ($return) return $r;

        echo $r;
        PerchUtil::flush_output();
    }

    function perch_content_create($key=false, $opts=false)
    {
        if ($key===false) return false;

        $default_opts = array(
            'page'            => false,
            'template'        => false,
            'multiple'        => false,
            'sort'            => false,
            'sort-order'      => false,
            'edit-mode'       => false,
            'searchable'      => true,
            'search-url'      => false,
            'add-to-top'      => false,
            'limit'           => false,
            'shared'          => false,
            'roles'           => false,
            'title-delimiter' => false,
            'columns'         => false,
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        if (!$opts['template']) return false;

        $Content = PerchContent::fetch();

        return $Content->create_region($key, $opts);
    }

    function perch_page_attributes($opts=array(), $return=false)
    {
        $Content = PerchContent::fetch();

        if (isset($opts['_id']) && $opts['_id']!='') {
            $Page = $Content->get_page_by_id($opts['_id']);
        }else{
            $Page = $Content->get_page();
        }


        if (is_object($Page)) {

            $default_opts = array(
                'template'      => $Page->pageAttributeTemplate(),
                'skip-template' => false,
            );

            if (is_array($opts)) {
                $opts = array_merge($default_opts, $opts);
            }else{
                $opts = $default_opts;
            }

            if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

            if ($opts['skip-template']) {
                return $Page->to_array();
            }

            $r = $Page->template_attributes($opts);

            if ($return) return $r;

            echo $r;
        }
        return false;
    }

    function perch_page_attribute($key=false, $opts=array(), $return=false)
    {
        if ($key==false) return;

        $Content = PerchContent::fetch();

        if (isset($opts['_id']) && $opts['_id']!='') {
            $Page = $Content->get_page_by_id($opts['_id']);
        }else{
            $Page = $Content->get_page();
        }


        if (is_object($Page)) {

            $default_opts = array(
                'template'      => $Page->pageAttributeTemplate(),
                'skip-template' => false,
            );

            if (is_array($opts)) {
                $opts = array_merge($default_opts, $opts);
            }else{
                $opts = $default_opts;
            }

            if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

            if ($opts['skip-template']) {
                $out = $Page->to_array();
                return $out['perch_'.$key];
            }

            $r = $Page->template_attribute($key, $opts);

            if ($return) return $r;

            echo $r;
        }
        return false;
    }

    function perch_page_get_attribute($key, $opts=array())
    {
        return perch_page_attribute($key, $opts, true);
    }

    function perch_page_attributes_extend($attrs)
    {
        PerchSystem::set_attr_vars($attrs);
    }

    function perch_page_modified($opts=array(), $return=false)
    {
        $Content = PerchContent::fetch();
        $Page = $Content->get_page();

        if (!$Page) return '';

        $default_opts = array(
            'format' => '%d %B %Y, %H:%M',
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

        if (isset($opts['data'])) PerchSystem::set_vars($opts['data']);

        $r = strftime($opts['format'], strtotime($Page->pageModified()));

        if ($return) return $r;

        echo $r;
    }
