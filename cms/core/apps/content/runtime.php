<?php
    include(PERCH_CORE.'/apps/content/PerchContent_Regions.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Region.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Items.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Item.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Pages.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent_Page.class.php');
    include(PERCH_CORE.'/apps/content/PerchContent.class.php');

    perch_content_check_preview();

    function perch_content($key=false, $return=false)
    {
        if ($key === false) {
            echo 'You must pass in a <em>name</em> for the content. e.g. <code style="color: navy;background: white;">&lt;' . '?php perch_content(\'Phone number\'); ?' . '&gt;</code>'; 
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
    }
        
    function perch_content_custom($key=false, $opts=false, $return=false)
    {
        if ($key === false) return ' ';

        if (isset($opts['skip-template']) && $opts['skip-template']==true) {
            $return  = true; 
            $postpro = false;
        }else{
            $postpro = true;
        }

        if (isset($opts['pagination_var']))    $opts['pagination-var'] = $opts['pagination_var'];

        $Content = PerchContent::fetch();    
        $out = $Content->get_custom($key, $opts);

        // Post processing - if there are still <perch:x /> tags
        if ($postpro && !is_array($out) && strpos($out, '<perch:')!==false) {
            $Template   = new PerchTemplate();
            $out        = $Template->apply_runtime_post_processing($out);
        }

        if ($return) return $out;
        echo $out;
    }
    
    function perch_content_check_preview()
    {
        if (!defined('PERCH_PREVIEW_ARG')) define('PERCH_PREVIEW_ARG', 'preview');
        
        if (isset($_GET[PERCH_PREVIEW_ARG])) {

            if ($_GET[PERCH_PREVIEW_ARG] == 'all') {
                $contentID = 'all';
            }else{
                $contentID  = (int)$_GET[PERCH_PREVIEW_ARG];
            }
            
            $rev         = false;
            $Users       = new PerchUsers;
            $CurrentUser = $Users->get_current_user();
            
            if (is_object($CurrentUser) && $CurrentUser->logged_in()) {
                $Content = PerchContent::fetch();
                $Content->set_preview($contentID, $rev);
            }
        }
    }
    
    function perch_content_search($key=false, $opts=false, $return=false)
    {   
        $key = trim(stripslashes($key));
        
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
        
        if (is_array($opts)) {
            $opts = array_merge($defaults, $opts);
        }else{
            $opts = $defaults;
        }

        if (isset($opts['hide_extensions']))    $opts['hide-extensions'] = $opts['hide_extensions'];
        if (isset($opts['from_path']))          $opts['from-path']       = $opts['from_path'];
        if (isset($opts['excerpt_chars']))      $opts['excerpt-chars']   = $opts['excerpt_chars'];
        
        $out = $Content->search_content($key, $opts);

        if ($opts['skip-template']) return $out;
        
        // Post processing - if there are still <perch:x /> tags
        if (strpos($out, '<perch:')!==false) {
            $Template   = new PerchTemplate();
            $out        = $Template->apply_runtime_post_processing($out);
        }
        
        if ($return) return $out;
        echo $out;
    }

    function perch_search_form($opts=false, $return=false)
    {
        $Perch = Perch::fetch();

        $defaults = array();
        $defaults['template'] = 'search-form.html';
        
        if (is_array($opts)) {
            $opts = array_merge($defaults, $opts);
        }else{
            $opts = $defaults;
        }

        $Template   = new PerchTemplate('search'.DIRECTORY_SEPARATOR.$opts['template']);
        $html = $Template->render(array());
        $html = $Template->apply_runtime_post_processing($html);
        
        if ($return) return $html;
        echo $html;
    }

    function perch_page_title($return=false) 
    {
        if ($return) return perch_pages_title(true);
        
        perch_pages_title();
    }

    function perch_pages_title($return=false)
    {
        $Pages = new PerchContent_Pages;
        $Perch = Perch::fetch();
        
        $Page = $Pages->find_by_path($Perch->get_page());
        
        $r = '';
        
        if (is_object($Page)) {
            $r = $Page->pageTitle();
        }
        
        if ($return) return $r;
        
        echo $r;
    }
    
    function perch_pages_navigation_text($return=false)
    {
        $Pages = new PerchContent_Pages;
        $Perch = Perch::fetch();
        
        $Page = $Pages->find_by_path($Perch->get_page());
        
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
        
        if ($opts['skip-template']) $return = true;
        
        $current_page = $Perch->get_page();
        
        if ($opts['from-path']=='*') {
            $opts['from-path'] = $current_page;
        }
        
        $r = $Pages->get_navigation($opts, $current_page);
        
        if ($return) return $r;
        
        echo $r;
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
        
        if ($opts['skip-template']) $return = true;
        
        $current_page = $Perch->get_page();

        $opts['from-path'] = $current_page;
        
        $r = $Pages->get_breadcrumbs($opts);
        
        if ($return) return $r;
        
        echo $r;
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

    function perch_get($var, $default=false)
    {
        if (isset($_GET[$var]) && $_GET[$var]!='') {
            return $_GET[$var];
        }
        return $default;
    }

    function perch_layout($file, $vars=array(), $return=false)
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
    }

    function perch_layout_var($var, $return=false)
    {
        $Perch = Perch::fetch();
        $var = $Perch->get_layout_var($var);

        if ($return) return $var;

        echo PerchUtil::html($var);
    }

    function perch_template($tpl, $vars=array(), $return=false)
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

        $r = strftime($opts['format'], strtotime($Page->pageModified()));

        if ($return) return $r;

        echo $r;
    }

    function perch_page_url($opts=array(), $return=false)
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
        
        $Pages = new PerchContent_Pages;

        $r = $Pages->get_full_url($opts);
        
        if ($return) return $r;
        
        echo PerchUtil::html($r);
    }

?>