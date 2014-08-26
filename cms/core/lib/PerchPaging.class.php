<?php

class PerchPaging
{
    public $enabled  = true;
    
    private $qs_param = 'page';
    
    private $per_page       = 10;
    private $start_position = 0;
    private $total          = 0;
    private $current_page   = 1;
    
    private $offset         = 0;

    private $base_url       = '';
    private $qs_char        = '';
    
    function __construct($qs_param=false)
    {
    	if ($qs_param) $this->qs_param = $qs_param;
    	
    	if(isset($_GET[$this->qs_param]) && $_GET[$this->qs_param]!='') {
    		$this->current_page = (int)$_GET[$this->qs_param];
    	}
    }    

    public function set_qs_param($qs_param)
    {
        if ($qs_param) $this->qs_param = $qs_param;
        
        if(isset($_GET[$this->qs_param]) && $_GET[$this->qs_param]!='') {
            $this->current_page = (int)$_GET[$this->qs_param];
        }
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
    
    public function to_array($opts=false)
    {
        $Perch = Perch::fetch();
        $request_uri = PerchUtil::html($Perch->get_page(1));
        
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
        
        
        $qs_char = '?';
        if (strpos($request_uri, $qs_char)!==false) $qs_char = '&amp;';
        
        $out    = array();
        $out['paging']          = true;
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
            
        if (!$this->is_first_page()) {

            if (($this->current_page()-1)==1) {
                // page 1, so don't include page=1
                $out['prev_url']    = preg_replace('/'.$this->qs_param.'=[0-9]+/', '', $request_uri);
            }else{
                $out['prev_url']    = preg_replace('/'.$this->qs_param.'=[0-9]+/', $this->qs_param.'='.($this->current_page()-1), $request_uri);
            }

            // remove any trailing '?'
            $out['prev_url']    = rtrim($out['prev_url'], '?');

            // remove any trailing '&amp;'
            if (substr($out['prev_url'], -5)=='&amp;') {
                $out['prev_url'] = substr($out['prev_url'], 0, strlen($out['prev_url'])-5);
            }


            $out['not_first_page'] = true;
            $out['prev_page_number'] = ($this->current_page()-1);
        }
        
        if (!$this->is_last_page()) {
            if (strpos($request_uri, $this->qs_param.'=') !== false) {
                $out['next_url']    = preg_replace('/'.$this->qs_param.'=[0-9]+/', $this->qs_param.'='.($this->current_page()+1), $request_uri);
                $out['next_page_number'] = ($this->current_page()+1);
            }else{
                $out['next_url']    = rtrim($request_uri) . $qs_char.$this->qs_param.'=2';
                $out['next_page_number'] = '2';
            }
            $out['not_last_page'] = true;
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

                $Template = new PerchTemplate('pagination/'.$template, 'pages');
                $out['page_links'] = $Template->render_group($page_links, true);
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

        if (strpos($request_uri, $this->qs_param.'=')===false) {
            $request_uri = rtrim($request_uri, '/').$this->qs_char.$this->qs_param.'=0';
        }
        $request_uri = preg_replace('/'.$this->qs_param.'=[0-9]+/', $this->qs_param.'='.($page_number), $request_uri);
        
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

}

?>