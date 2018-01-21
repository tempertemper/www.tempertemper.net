<?php

class PerchPaging
{
    public $enabled  = true;
    public $sortable = false;
    
    private $qs_param      = 'page';
    private $qs_param_sort = 'sort';
    
    private $per_page         = 10;
    private $start_position   = 0;
    private $total            = 0;
    private $current_page     = 1;
    
    private $offset           = 0;
    
    private $base_url         = '';
    private $qs_char          = '';
    private $page_pattern     = '';
    private $page_replacement = '';
    private $sort_pattern     = '';
    private $sort_replacement = '';
    private $use_qs           = true;

    private $sort_key         = null;
    private $sort_dir         = null;

    function __construct($qs_param=false, $qs_param_sort=false)
    {
        $this->set_qs_param($qs_param);
        if (PERCH_RUNWAY) {
            $this->sortable = true;
            $this->set_qs_param_sort($qs_param_sort);
        }
    }    

    public function set_qs_param($qs_param)
    {
        if ($qs_param) $this->qs_param = $qs_param;
        
        $Perch = Perch::fetch();

        $this->page_pattern     = '\b'.$this->qs_param.'=[0-9]+\b';
        $this->page_replacement = $this->qs_param.'=%d';
        
        if (!$Perch->admin && PERCH_RUNWAY) {
            $paging_conf = PerchConfig::get('paging');
            if ($paging_conf && isset($paging_conf['pattern']) && isset($paging_conf['replacement'])) {
                $this->page_pattern     = $paging_conf['pattern'];
                $this->page_replacement = $paging_conf['replacement'];
                $this->use_qs = false;
            }
        }
        
        if (PerchUtil::get($this->qs_param)) {
            $this->current_page = (int)PerchUtil::get($this->qs_param);
        }
    }

    public function set_qs_param_sort($qs_param_sort)
    {
        $Perch = Perch::fetch();

        if ($Perch->admin && PERCH_RUNWAY) {
            if ($qs_param_sort) $this->qs_param_sort = $qs_param_sort;

            $this->sort_pattern     = '\b'.$this->qs_param_sort.'=([a-zA-Z0-9_\^]+)\b';
            $this->sort_replacement = $this->qs_param_sort.'=%s';

            list($key, $dir) = $this->get_custom_sort_options();
            $this->sort_key = $key;
            $this->sort_dir = $dir;
        }
    }

    public function get_sorting_qs_param()
    {
        return $this->qs_param_sort;
    }
    
    public function select_sql()
    {
        if ($this->enabled) {
            return 'SELECT SQL_CALC_FOUND_ROWS DISTINCT';
        }
        return 'SELECT';
    }
    
    public function limit_sql()
    {
        if ($this->enabled) {
            return 'LIMIT ' . $this->lower_bound() . ', ' . $this->per_page();
        }
        return '';
    }

    public function total_count_sql()
    {
        if ($this->enabled) {
            return 'SELECT FOUND_ROWS() AS `count` ';
        }
        return '';
    }

    public function enable()
    {
        $this->enabled  = true;
    }
    
    public function disable()
    {
        $this->enabled = false;
    }
    
    public function enabled()
    {
        return $this->enabled;
    }
    
    public function set_per_page($per_page=10)
    {
        $this->per_page = $per_page;
    }
    
    public function per_page()
    {
        return $this->per_page;
    }
    
    public function set_start_position($start_position=0)
    {
        $this->start_position = $start_position;
    }
    
    public function start_position()
    {
        return $this->start_position;
    }
    
    public function offset()
    {
        return $this->offset;
    }
    
    public function set_offset($offset=0)
    {
        $this->offset = $offset;
    }
    
    public function set_total($total)
    {
        $this->total    = $total;
    }
    
    public function total()
    {
        return $this->total;
    }
    
    public function lower_bound()
    {
        return (($this->per_page * $this->current_page) - $this->per_page) + $this->offset;
    }
    
    public function upper_bound()
    {
        $ub = $this->lower_bound() + $this->per_page - 1;
        
        if ($this->total != 0 && $ub > $this->total) {
            return $this->total;
        }
        
        return $ub;
    }
    
    public function number_of_pages()
    {
        if ($this->total==0) return 1;
        if ($this->per_page==0) return 1;
        return ceil((0-$this->offset + $this->total) / $this->per_page);
    }
    
    public function is_first_page()
    {
        if ($this->current_page == 1) {
            return true;
        }
        
        return false;
    }
    
    public function is_last_page()
    {
        if ($this->current_page == $this->number_of_pages()) {
            return true;
        }
        
        return false;
    }
    
    public function current_page()
    {
        return $this->current_page;
    }

    public function sort_link($col)
    {
        $Perch       = Perch::fetch();
        $request_uri = PerchUtil::html($Perch->get_page(1));
        $qs_char     = '?';
        if (strpos($request_uri, $qs_char)!==false) $qs_char = '&amp;';

        if (preg_match('#'.$this->sort_pattern.'#', $request_uri, $match)) {
            switch($match[1]) {
                case $col:
                    $sub = '^'.$col;
                    break;

                case '^'.$col:
                    $sub = false;
                    break;

                default:
                    $sub = $col;
                    break;
            }
            if ($sub) {
                return $this->sort_link_replacement(preg_replace('#'.$this->sort_pattern.'#', sprintf($this->sort_replacement, $sub), $request_uri));
            } else {
                if (strpos($request_uri, '?'.$this->qs_param_sort)!==false) {
                    $char = '\?';
                } else {
                    $char = '&amp;';
                }
                return $this->sort_link_replacement(preg_replace('#'.$char . $this->sort_pattern.'#', '', $request_uri));    
            }
            
        } else {
            return $this->sort_link_replacement(rtrim($request_uri) . $qs_char. sprintf($this->sort_replacement, $col));
        }
    }

    private function sort_link_replacement($uri)
    {
        $uri = preg_replace('#'.$this->page_pattern.'#', sprintf($this->page_replacement, 1), $uri);

        $uri = str_replace('/&amp;', '/?', $uri);

        return $uri;
    }

    public function get_custom_sort_options(PerchTemplate $Template = null)
    {
        $sort_arg = PerchUtil::get($this->qs_param_sort);
        if ($sort_arg) {
            if (strpos($sort_arg, '^') === 0) {
                $dir = 'DESC';
                $key = str_replace('^', '', $sort_arg);
            } else {
                $dir = 'ASC';
                $key = $sort_arg;
            }

            // _title isn't indexed, so look up the indexed field from the template and use that instead 
            if ($Template && $key == '_title') {
                $key = $Template->find_title_field_id();
            }


            return [$key, $dir];
        }

        return [null, null];
    }
    
    public function to_array($opts=false)
    {
        $request_uri = $this->prep_request_uri($opts);

        $qs_char = '?';
        if (strpos($request_uri, $qs_char)!==false) $qs_char = '&amp;';
        if (!$this->use_qs) $qs_char = '';
        
        $out    = array();
        $out['paging']          = ($this->number_of_pages() > 1 ? true : false);
        $out['total']           = $this->total();
        $out['number_of_pages'] = $this->number_of_pages();
        $out['total_pages']     = $this->number_of_pages();
        $out['per_page']        = $this->per_page();
        $out['current_page']    = $this->current_page();
        
        $out['lower_bound']     = $this->lower_bound()+1;
        $out['upper_bound']     = $this->upper_bound()+1;
        
        if ($this->total != 0 && $out['upper_bound'] > $this->total) {
            $out['upper_bound'] = $this->total;
        }
                
        $out['prev_url']        = '';
        $out['next_url']        = '';

        $out['prev_page_number'] = '';
        $out['next_page_number'] = '';

        $out['first_page_url'] = preg_replace('#'.$this->page_pattern.'#', sprintf($this->page_replacement, 1), $request_uri);
        $out['last_page_url']  = preg_replace('#'.$this->page_pattern.'#', sprintf($this->page_replacement, $this->number_of_pages()), $request_uri);
            
        if (!$this->is_first_page()) {

            $prev_page_number = ($this->current_page()-1);

            if (strpos($request_uri, '?')===false) {
                $request_uri .= '?'.$this->qs_param.'='.$prev_page_number;
            }

            if ($prev_page_number==1) {
                // page 1, so don't include page=1
                $out['prev_url']    = preg_replace('#'.$this->page_pattern.'#', '', $request_uri);
            }else{             
                $out['prev_url']    = preg_replace('#'.$this->page_pattern.'#', sprintf($this->page_replacement, $prev_page_number), $request_uri);
            }

            // remove any trailing '?'
            $out['prev_url']    = rtrim($out['prev_url'], '?');

            // remove any trailing '&amp;'
            if (substr($out['prev_url'], -5)=='&amp;') {
                $out['prev_url'] = substr($out['prev_url'], 0, strlen($out['prev_url'])-5);
            }

            $out['not_first_page'] = true;
            $out['prev_page_number'] = $prev_page_number;
        
        } else {
            $out['first_page'] = true;
        }
        
        if (!$this->is_last_page()) {

            $next_page_number = ($this->current_page()+1);

            if (preg_match('#'.$this->page_pattern.'#', $request_uri)) {
                $out['next_url']    = preg_replace('#'.$this->page_pattern.'#', sprintf($this->page_replacement, $next_page_number), $request_uri);
                $out['next_page_number'] = $next_page_number;
            }else{
                $out['next_url']    = rtrim($request_uri) . $qs_char. sprintf($this->page_replacement, $next_page_number);
                $out['next_page_number'] = $next_page_number;
            }

            $out['not_last_page'] = true;
        
        } else {
            $out['last_page'] = true;
        }

        // Page links
        if (isset($opts['page-links']) && $opts['page-links']) {
            $this->base_url = $request_uri;
            $this->qs_char = $qs_char;

            if (isset($opts['page-link-style']) && $opts['page-link-style']=='all') {           
                $page_links = $this->get_page_links();
            }else{
                $page_links = $this->get_page_links(true);
            }
          

            if (PerchUtil::count($page_links)) {

                $template = 'page-links.html';
                if (isset($opts['page-link-template'])) {
                    $template = $opts['page-link-template'];
                }

                if ($template) {
                    $Template = new PerchTemplate('pagination/'.$template, 'pages');
                    $out['page_links'] = $Template->render_group($page_links, true);
                } else {
                    $out['page_links'] = $page_links;
                }

                
            }
        }    
       
        return $out;
    }

    public function get_page_links($shortened=false)
    {
        $total_pages  = (int)$this->number_of_pages();
        $current_page = (int)$this->current_page();

        if ($total_pages<2) return false;

        $links = array();

        // Full link set
        if (!$shortened) {
            for($i=1; $i<=$total_pages; $i++) {
                $links[] = $this->_create_page_link($i);
            }
            return $links;
        }

    
        // Shorted version

        $links[] = $this->_create_page_link(1);

        if ($current_page > 2) {

            if ($current_page>3 && $total_pages>3) {
                $links[] = $this->_create_spacer();    
            }
            
            if ($current_page==$total_pages && $total_pages>3) {
                $links[] = $this->_create_page_link($current_page-2);
            } 
            $links[] = $this->_create_page_link($current_page-1);      
        }

        if ($current_page!=1 && $current_page!=$total_pages) {
            $links[] = $this->_create_page_link($current_page);
        }

        if ($current_page<($total_pages-1)) {
            $links[] = $this->_create_page_link($current_page+1);

            if ($current_page==1 && $total_pages>3) {
                $links[] = $this->_create_page_link($current_page+2);
            }

            if ($total_pages>3 && ($current_page<$total_pages-2)) {
                $links[] = $this->_create_spacer();    
            }
            
        }

        $links[] = $this->_create_page_link($total_pages);

        return $links;
    }

    

    private function _create_page_link($page_number) 
    {
        $out = array();
        $request_uri = $this->base_url;

        if (preg_match('#'.$this->page_pattern.'#', $request_uri)==false) {
            $request_uri = rtrim($request_uri) . $this->qs_char. sprintf($this->page_replacement, '0');
        }

        $request_uri = preg_replace('#'.$this->page_pattern.'#', sprintf($this->page_replacement, $page_number), $request_uri);
        
        $out['url'] = $request_uri;
        $out['page_number'] = $page_number;
        
        if ((int)$this->current_page() == $page_number){
            $out['selected'] = true;
        }

        return $out;

    }

    private function _create_spacer()
    {
         return array(
            'spacer' => true,
            'url' => false,
            'page_number' => '…',
            );
    }

    private function prep_request_uri($opts=false) 
    {
        $Perch = Perch::fetch();

        $request_uri = PerchUtil::html($Perch->get_page(1));

        #PerchUtil::debug('Pagination base url: '.$request_uri);
        
        if (is_array($opts)) {
            if (isset($opts['hide-extensions']) && $opts['hide-extensions']==true) {
                
                if (strpos($request_uri, '.')) {
                    $query = '';
                    if ($qpos = strpos($request_uri, '?')) {
                        $query = substr($request_uri, $qpos);
                    }
                    $parts = explode('.', $request_uri);
                    array_pop($parts);
                    $request_uri = implode('.', $parts);
                    $request_uri .= $query;
                }
                
            }
        }

        return $request_uri;
    }

}
