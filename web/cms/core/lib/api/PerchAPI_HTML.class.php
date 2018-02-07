<?php

use \DrewM\Selecta\Selecta;

class PerchAPI_HTML
{
    public $app_id = false;
    public $version = 1.0;

    private $formatters = array();

    private $Lang = false;

    function __construct($version=1.0, $app_id, $Lang)
    {
        $this->app_id = $app_id;
        $this->version = $version;

        $this->Lang = $Lang;
    }

    public function set_formatter($key, $callback)
    {
        $this->formatters[$key] = $callback;
    }

    public function get_formatter($key)
    {
        if (isset($this->formatters[$key])) return $this->formatters[$key];

        return false;
    }

    public function set_lang($Lang)
    {
        $this->Lang = $Lang;
    }

    public function title_panel_start()
    {
    }

    public function title_panel_end()
    {
    }

    public function side_panel_start()
    {
		include (PERCH_PATH.'/core/inc/sidebar_start.php');
    }

    public function side_panel_end()
    {
		include (PERCH_PATH.'/core/inc/sidebar_end.php');
    }

    public function main_panel_start()
    {
		include (PERCH_PATH.'/core/inc/main_start.php');
    }

    public function main_panel_end()
    {
		include (PERCH_PATH.'/core/inc/main_end.php');
    }


    public function heading1($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return '<h1>'.$string.'</h1>';
    }

    public function heading2($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return '<h2 class="divider"><div>'.$string.'</div></h2>';
    }

    public function heading3($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return '<h3><span>'.$string.'</span></h3>';
    }

    public function heading3filter($string, $opts, $arg='by')
    {
		$Perch = new PerchAdmin;

        $s = '<h3 class="em"><span>'.$this->Lang->get($string).'<span class="filter">';

        foreach($opts as $opt) {
            $s .= '<a href="'.PerchUtil::html($Perch->get_page()).'?'.$arg.'='.$opt['slug'].'" class="filter-'.$opt['slug'].' '.($opt['selected']?'selected':'').'">'.$this->Lang->get($opt['title']).'</a> ';
        }

        $s .= '</span></span></h3>';

        return $s;
    }

    public function heading4($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return '<h4>'.$string.'</h4>';
    }


    public function para($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return '<p>'.$string.'</p>';
    }

    public function form_help($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return '<p class="form-help">'.$string.'</p>';
    }

    public function warning_message($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);
        $icon = PerchUI::icon('core/alert');
    
        return $this->wrap('div[role=alert].notification.notification-warning', $icon.$string);

    }

    public function success_message($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        $icon = PerchUI::icon('core/circle-check');
    
        return $this->wrap('div[role=alert].notification.notification-success', $icon.$string);
    }

    public function failure_message($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        $icon = PerchUI::icon('core/face-pain');
    
        return $this->wrap('div[role=alert].notification.notification-alert', $icon.$string);

    }

    public function warning_block($heading, $message)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);

        $icon = PerchUI::icon('core/alert');

        $heading = $this->wrap('h2.notification-heading', $icon.' '.$this->Lang->get($heading));
        $body    = $this->wrap('p', $this->Lang->get($message, $args));
    
        return $this->wrap('div[role=alert].notification-block.notification-warning', $heading.$body);

    }

    public function info_block($heading, $message)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);

        $icon = PerchUI::icon('core/info-alt');

        $heading = $this->wrap('h2.notification-heading', $icon.' '.$this->Lang->get($heading));
        $body    = $this->wrap('p', $this->Lang->get($message, $args));
    
        return $this->wrap('div[role=alert].notification-block.notification-info', $heading.$body);

    }

    public function success_block($heading, $message)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);

        $icon = PerchUI::icon('core/circle-check');

        $heading = $this->wrap('h2.notification-heading', $icon.' '.$this->Lang->get($heading));
        $body    = $this->wrap('p', $this->Lang->get($message, $args));
    
        return $this->wrap('div[role=alert].notification-block.notification-success', $heading.$body);
    }

    public function failure_block($heading, $message)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);

        $icon = PerchUI::icon('core/face-pain');

        $heading = $this->wrap('h2.notification-heading', $icon.' '.$this->Lang->get($heading));
        $body    = $this->wrap('p', $this->Lang->get($message, $args));
    
        return $this->wrap('div[role=alert].notification-block.notification-alert', $heading.$body);
    }

    public function icon($type='tick', $alt='Success')
    {
        return "Deprecated. Use PerchUI::icon() instead.";
    }

    public function paging($Paging)
    {
        $paging = $Paging->to_array([
                'page-links' => true,
                'page-link-template' => false,
            ]);

        if ($paging['total_pages'] < 2) return '';

        $s  = '<nav class="pagination" role="navigation" aria-label="'.$this->Lang->get('Pagination').'">';

        $s .= '<ul class="pagination-prev-group">';

        $state = 'disabled';
        if (isset($paging['not_first_page']) && $paging['not_first_page']) {
            $state = '';
        }

        $s .= '<li class="pagination-first">
                    <a '.$state.' href="'.$paging['first_page_url'].'" title="'.$this->Lang->get('First page').'" aria-label="'.$this->Lang->get('First page').'" class="button button-icon icon-left '.$state.'">
                    <div>'.PerchUI::icon('core/o-navigate-double-left', 8).'<span>'.$this->Lang->get('First').'</span>
                    </div></a>
                </li>';


        $s .= '<li class="pagination-prev">
                    <a '.$state.' href="'.$paging['prev_url'].'" rel="prev" title="'.$this->Lang->get('Previous page').'" aria-label="'.$this->Lang->get('Previous page').'" class="button button-icon icon-left '.$state.'">
                    <div>'.PerchUI::icon('core/o-navigate-left', 8).'<span>'.$this->Lang->get('Prev').'</span></div></a>
                </li>';

        $s .= '</ul>';
        

        $page_links = $paging['page_links'];

        if (PerchUtil::count($page_links)) {

            $s .= '<ul class="pagination-numbers">';

            foreach($page_links as $page) {
                $url = $page['url'];
                $num = $page['page_number'];
                $class = (isset($page['selected']) ? 'pagination-number pagination-current' : 'pagination-number ');
                $title = $this->Lang->get('Page %d', $num);
                $aria_label = false;

                if (isset($page['selected'])) {
                    $title = $this->Lang->get('Page %d, current page', $num);
                    $aria_label = $this->Lang->get('%d, current page', $num);
                }
                
                $s .= '<li class="'.$class.'">
                        <a href="'.$url.'" title="'.$title.'" aria-label="'.$aria_label.'" class="button button-simple">'.$num.'</a>
                        </li>';
            }

            $s .= '</ul>';

        }


        $s .= '<ul class="pagination-next-group">';

        $state = 'disabled';
        if (isset($paging['not_last_page']) && $paging['not_last_page']) {
            $state = '';
        }

        $s .= '<li class="pagination-next">
                    <a '.$state.' href="'.$paging['next_url'].'" rel="next" title="'.$this->Lang->get('Next page').'" aria-label="'.$this->Lang->get('Next page').'" class="button button-icon icon-right '.$state.'">
                    <div>'.PerchUI::icon('core/o-navigate-right', 8).'<span>'.$this->Lang->get('Next').'</span></div></a>
                </li>';

        $s .= '<li class="pagination-last">
                    <a '.$state.' href="'.$paging['last_page_url'].'" title="'.$this->Lang->get('Last page').'" aria-label="'.$this->Lang->get('Last page').'" class="button button-icon icon-right '.$state.'">
                    <div>'.PerchUI::icon('core/o-navigate-double-right', 8).'<span>'.$this->Lang->get('Last').'</span></div></a>
                </li>';

        $s .= '</ul>';

        
        $s .= '</nav>';

        return $s;
    }

    public function old_paging($Paging)
    {
        $paging = $Paging->to_array();

        if ((int)$paging['total_pages']<2) return '';

        $s = '<div class="paging">';

        if (isset($paging['not_first_page']) && $paging['not_first_page']) {
            $s .= '<a class="paging-prev button" href="'.$paging['prev_url'].'">'.$this->Lang->get('Prev').'</a> ';
        }

        $s .= '<span class="paging-status">'.$this->Lang->get('Page %s of %s', $paging['current_page'], $paging['total_pages']).'</span>';


        if (isset($paging['not_last_page']) && $paging['not_last_page']) {
            $s .= '<a class="paging-next button" href="'.$paging['next_url'].'">'.$this->Lang->get('Next').'</a> ';
        }

        $s .= '</div>';

        return $s;
    }



    public function encode($string, $quotes=false, $double_encode=false)
    {
        return PerchUtil::html($string, $quotes, $double_encode);
    }

	public function subnav($CurrentUser, $opts)
	{
		return PerchUtil::subnav($CurrentUser, $opts, $this->Lang);
	}

    public function listing($rows, $headings, $values, $paths, $privs)
    {
        $s = '';
        if (PerchUtil::count($rows)) {

            $CurrentUser = $privs['user'];
            $edit_link   = false;
            if ($privs['edit']===false || ($CurrentUser && $CurrentUser->has_priv($privs['edit']))) {
                $edit_link = true;
            }


            $s .= '<table class="d">
                    <thead>
                        <tr>';
            foreach($headings as $heading) {
                $s .= '<th>'.$this->Lang->get($heading).'</th>';
            }
            $s .= '     <th class="action last"></th>
                        </tr>
                    </thead>
                    <tbody>';



            foreach($rows as $row) {

                if (is_object($row)) {
                    $row_array = $row->to_array();
                }

                $i = 0;

                $s.= '<tr>';

                foreach($values as $val) {

                    $formatting_code = false;

                    if (strpos($val, '|')>0) {
                        $parts = explode('|', $val);
                        $formatting_code = $parts[0];
                        $val = $parts[1];
                    }

                    if ($i==0) {
                        $s .= '<td class="primary">';
                        if ($edit_link) $s .= '<a href="'.$paths['edit'].'/?id='.$row->id().'">';
                        $s .= (isset($row_array[$val]) ? $row_array[$val] : $row->$val());
                        if ($edit_link) $s .= '</a>';
                        $s .= '</td>';
                    }else{
                        $s .= '<td>';

                        // link?
                        if (strpos($val, '/')!==false) {

                            if ($edit_link) $s .= '<a href="'.$val.'/?id='.$row->id().'">';
                                $s .= $headings[$i];
                            if ($edit_link) $s .= '</a>';

                        }else{
                            $v_v = (isset($row_array[$val]) ? $row_array[$val] : $row->$val());
                            if (is_array($v_v) && isset($v_v['value'])) {
                                $s .= $this->format_list_value($formatting_code, $v_v['value']);
                            }else{
                                $s .= $this->format_list_value($formatting_code, $v_v);
                            }
                        }


                        $s .= '</td>';
                    }

                    $i++;
                }

                if ($privs['delete']!==null && ($privs['delete']===false || ($CurrentUser && $CurrentUser->has_priv($privs['delete'])))) {
                    if (isset($privs['not-inline'])) {
                        $s .= '<td><a href="'.$paths['delete'].'/?id='.$row->id().'" class="delete">'.$this->Lang->get('Delete').'</a></td>';
                    }else{
                        $s .= '<td><a href="'.$paths['delete'].'/?id='.$row->id().'" class="delete inline-delete">'.$this->Lang->get('Delete').'</a></td>';
                    }

                }else{
                    $s .= '<td></td>';
                }
                $s .= '</tr>';
            }



            $s .= '   </tbody>
                    </table>';
        }
        return $s;
    }

    public function smartbar()
    {
        $items  = func_get_args();
        $s = '<div class="smartbar">
                <ul>';
        foreach($items as $item) {
            $s .= $item;
        }
        $s .= '</ul>
        </div>';

        return $s;
    }

    public function smartbar_breadcrumb()
    {
        $items  = func_get_args();
        $active = array_shift($items);
        $s = '';

        if (PerchUtil::count($items)) {
            if ($active) {
                $s .= '<li class="selected">';
            }else{
                $s .= '<li>';
            }
            $s .= '<span class="set">';

            $links = array();

            for($i=0; $i<count($items); $i++) {
                $item = $items[$i];
                if ($i==count($items)-1) {
                    // last item
                    $link = '<a';
                }else{
                    $link = '<a class="sub"';
                }

                $link .= ' href="'.PerchUtil::html($item['link'], true).'">';
                $link .= PerchUtil::html($item['label']);
                $link .= '</a>';

                $links[] = $link;
            }

            $s .= implode('<span class="sep icon"></span>', $links);

            $s .= '</span>';
            $s .= '</li>';
        }

        return $s;
    }

    public function smartbar_link($active, $item, $end=false)
    {
        $s = '<li';

        $class = array();

        if ($active) $class[] = 'selected';
        if ($end) $class[] = 'fin';

        if (count($class)) {
            $s.= ' class="'.implode(' ', $class).'"';
        }

        $s .= '>';
        $s .= '<a ';

        if (isset($item['class'])) {
            $s.=  ' class="'.$item['class'].'"';
        }

        $s .= 'href="'.PerchUtil::html($item['link'], true).'">';
        $s .= PerchUtil::html($item['label']);
        $s .= '</a>';

        $s .= '</li>';

        return $s;
    }

    public function title_panel($def, $CurrentUser = null)
    {
        if (isset($def['heading'])) {
            $heading = $this->wrap('h1', $this->encode($def['heading']));
        } else {
            $heading = '';
        }

        #if (isset($def['notifications']) && $def['notifications']) {
            $heading .= $this->wrap('div.notifications', '');
        #}

        $button = '';

        if (isset($def['button'])) {

            $show_button = true;

            if (isset($def['button']['priv'])) {

                if (is_null($CurrentUser)) {
                    die(sprintf('title_panel() is checking for button privilege "%s" but $CurrentUser has not been passed in as the second argument.', $def['button']['priv']));
                }

                $show_button = false;
                if ($CurrentUser->has_priv($def['button']['priv'])) {
                    $show_button = true;
                }
            }

            if ($show_button) {
                    $label = '';
                $class = '.button';
                if (isset($def['button']['icon'])) {
                    $label .= PerchUI::icon($def['button']['icon'], 10);
                    $class .= '.button-icon.icon-left';
                }
                $label .= $this->wrap('span', $this->encode($def['button']['text']));
                $path = PERCH_LOGINPATH . $def['button']['link'];
                $button = $this->wrap('div.buttons a'.$class.'[href='.$path.'] div', $label);    
            }
            
        } 

        if (isset($def['button-placeholder'])) {
            $button = $this->build('div.buttons');
        }

        if (isset($def['form'])) {
            $button = $this->wrap('div', $def['form']['button']);
            $button = $this->wrap('form[method=post][action='.$def['form']['action'].'].buttons', $button);
        }

        $Alert = new PerchAlert;

        if (PERCH_DEBUG) {
            $trace = debug_backtrace();
            PerchUtil::debug("File: ".str_replace(realpath(__DIR__.'/../../../'), '', $trace[0]['file']));    
        }
        

        return $this->wrap('header.title-panel', $heading.$button).$Alert->output(true);
    }

    public function build($selector, $contents='', $open_tags=true, $close_tags=true)
    {
        return Selecta::build($selector, $contents, $open_tags, $close_tags);
    }

    public function wrap($selector, $contents='')
    {
        return Selecta::wrap($selector, $contents);
    }

    public function open($selector)
    {
        return Selecta::open($selector);
    }

    public function close($selector)
    {
        return Selecta::close($selector);
    }

    public function submit_bar($def)
    {
        $s = $def['button'];

        if (isset($def['cancel_link'])) {
            $cancel_link = PERCH_LOGINPATH.$def['cancel_link'];
            $s .= ' ' . $this->Lang->get('or') .' '. $this->wrap("a[href=$cancel_link]", $this->Lang->get('Cancel'));
        }

        $s = $this->wrap('div.submit-bar div.submit-bar-actions', $s);

        return $s;
    }


    private function format_list_value($code, $value)
    {
        if ($code===false) return $value;

        $formatter = $this->get_formatter($code);
        if ($formatter && is_callable($formatter)) {
            return call_user_func($formatter, $value);
        }

        switch($code) {

            case 'active':
                if ($value==0) return '';
                if ($value==1) {
                    return '<span class="icon ok"></span>';
                }
                break;

            case 'implode':
                if (!is_array($value)) return '';
                return implode(', ', $value);
                break;

        }

        return $value;
    }
}
