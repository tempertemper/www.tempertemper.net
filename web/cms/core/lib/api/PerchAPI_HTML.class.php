<?php

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

        return '<h2>'.$string.'</h2>';
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

        return '<p class="alert notice">'.$string.'</p>';
    }

    public function success_message($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return '<p class="alert success">'.$string.'</p>';
    }

    public function failure_message($string)
    {
        $args = func_get_args();
        array_shift($args);

        $string =  $this->Lang->get($string, $args);

        return '<p class="alert error">'.$string.'</p>';
    }

    public function icon($type='tick', $alt='Success')
    {
        switch($type) {

            case 'tick':
                $file = 'icon_tick.gif';
                break;
            case 'warn':
                $file = 'icon_warn.gif';
                break;
            case 'draft':
                $file = 'icon_draft.png';
                break;
            case 'notice':
                $file = 'icon_notice.gif';
                break;
            case 'page-preview':
                $file = 'icon_page_preview.png';
                break;
            case 'page':
                $file = 'icon_page.png';
                break;
            case 'pages':
                $file = 'icon_pages.png';
                break;
            case 'undo':
                $file = 'icon_undo.png';
                break;
            case 'user':
                $file = 'icon_user.png';
                break;

            default:
                $file = false;
                break;
        }

        if ($file) {
            return '<img src="'.PERCH_LOGINPATH.'/assets/img/'.$file.'" alt="'.$this->encode($alt).'" />"';
        }

    }

    public function paging($Paging)
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
        $s = '<ul class="smartbar">';
        foreach($items as $item) {
            $s .= $item;
        }
        $s .= '</ul>';

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
