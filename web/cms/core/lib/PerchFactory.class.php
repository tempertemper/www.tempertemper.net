<?php

class PerchFactory
{
    protected $db;
    protected $cache                  = false;
    protected $api                    = false;

    protected $namespace              = 'content';
    protected $index_table            = false;

    protected $bypass_categories      = false;
    protected $bypass_tags            = false;

    protected $default_sort_direction = 'ASC';

    protected $runtime_restrictions    = array();

    public $dynamic_fields_column     = false;

    private $cats_cache               = array();
    private $Categories               = false;

    function __construct($api=false)
    {
        if ($api) $this->api = $api;

        $this->db       = PerchDB::fetch();

        if (defined('PERCH_DB_PREFIX')) {
            $this->table    = PERCH_DB_PREFIX.$this->table;
        }

        if (!$this->dynamic_fields_column) {
            $this->dynamic_fields_column = str_replace('ID', 'DynamicFields', $this->pk);
        }

    }

    public function find($id)
    {
        $sql    = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pk . '='. $this->db->pdb($id) .$this->standard_restrictions().' LIMIT 1';
        $result = $this->db->get_row($sql);

        if (is_array($result)) {
            return $this->return_instance($result);
        }

        return false;
    }

    public function all($Paging=false)
    {
        $sort_val = null;
        $sort_dir = null;

        if ($Paging && $Paging->enabled()) {
            $sql = $Paging->select_sql();
            list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();
        }else{
            $sql = 'SELECT';
        }

        $sql .= ' *
                FROM ' . $this->table;

        $restrictions = $this->standard_restrictions();

        if ($restrictions!='') {
            $sql .= ' WHERE 1=1 '.$restrictions;
        }

        if ($sort_val) {
            $sql .= ' ORDER BY '.$sort_val.' '.$sort_dir;
        } else {

            if (isset($this->default_sort_column)) {
                $sql .= ' ORDER BY ' . $this->default_sort_column . ' '.$this->default_sort_direction;
            }
        }


        if ($Paging && $Paging->enabled()) {
            $sql .=  ' '.$Paging->limit_sql();
        }

        $results = $this->db->get_rows($sql);

        if ($Paging && $Paging->enabled()) {
            $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
        }

        return $this->return_instances($results);
    }

    public function first()
    {
        $sql = 'SELECT * FROM ' . $this->table;

        if (isset($this->default_sort_column)) {
            $sql .= ' ORDER BY ' . $this->default_sort_column . ' '.$this->default_sort_direction;
        }

        $sql .= ' LIMIT 1 ';
     
        $result = $this->db->get_row($sql);

        return $this->return_instance($result);
    }

    /**
     * Get one item by the specified column. e.g. get_one_by('widgetID', 232) would select from this table where widgetID=232.
     *
     * @param string $col
     * @param string $val
     * @param string $order_by_col
     * @return void
     * @author Drew McLellan
     */
    public function get_one_by($col, $val, $order_by_col=false)
    {
        $sql    = 'SELECT * FROM ' . $this->table . ' WHERE ' . $col . '='. $this->db->pdb($val) .' '.$this->standard_restrictions();

        if ($order_by_col) $sql .= ' ORDER BY '.$order_by_col;

        $sql .= ' LIMIT 1';

        $result = $this->db->get_row($sql);

        if (is_array($result)) {
            return $this->return_instance($result);
        }

        return false;
    }

    /**
     * Get a collection of items where the given column matches the given value. e.g. get_by('catID', 2) would get all rows with catID=2.
     * If $val is an array, does a SQL WHERE IN(array)
     *
     * @param string $col
     * @param string $val
     * @param string $order_by_col
     * @return void
     * @author Drew McLellan
     */
    public function get_by($col, $val, $order_by_col=false, $Paging=false)
    {
        $sort_val = null;
        $sort_dir = null;

        if ($Paging && $Paging->enabled()) {
            $select = $Paging->select_sql();
            list($sort_val, $sort_dir) = $Paging->get_custom_sort_options();
        }else{
            $select = 'SELECT';
        }

        if (is_array($val)) {
            $sql    = $select . ' * FROM ' . $this->table . ' WHERE ' . $col . ' IN ('. $this->db->implode_for_sql_in($val) .') '.$this->standard_restrictions();
        }else{
            $sql    = $select . ' * FROM ' . $this->table . ' WHERE ' . $col . '='. $this->db->pdb($val) .' '.$this->standard_restrictions();
        }


        if ($sort_val) {
            $sql .= ' ORDER BY '.$sort_val.' '.$sort_dir;
        } else {
            if ($order_by_col) {
                $sql .= ' ORDER BY '.$order_by_col;
            }else{
                if ($this->default_sort_column) $sql .= ' ORDER BY '.$this->default_sort_column.' '.$this->default_sort_direction;
            }
        }

        if (is_object($Paging) && $Paging->enabled()){
            $limit  = ' LIMIT ' . $Paging->lower_bound() . ', ' . $Paging->per_page();
            $sql    .= $limit;
        }

        $rows = $this->db->get_rows($sql);

        if (is_object($Paging) && $Paging->enabled()){
            $sql    = "SELECT FOUND_ROWS() AS count";
            $total  = $this->db->get_value($sql);
            $Paging->set_total($total);
        }

        return $this->return_instances($rows);
    }

    /**
     * Gets recent items, sorted by date, limited by an int or Paging class
     *
     * @param obj $Paging_or_limit Paging class or int for basic limit
     * @param bool $use_modified_date Use the modified date instead of created
     * @return array Array of singular objects
     * @author Drew McLellan
     */
    public function get_recent($Paging_or_limit=10, $use_modified_date=false)
    {
        if ($use_modified_date) {
            if ($this->modified_date_column) {
                $datecol = $this->modified_date_column;
            }else{
                $datecol = str_replace('ID', 'Modified', $this->pk);
            }
        }else{
            if ($this->created_date_column) {
                $datecol = $this->created_date_column;
            }else{
                $datecol = str_replace('ID', 'Created', $this->pk);
            }
        }

        $Paging = false;
        $limit  = false;

        if (is_object($Paging_or_limit)) {
            $Paging = $Paging_or_limit;
            $select = 'SELECT SQL_CALC_FOUND_ROWS DISTINCT ';
        }else{
            $limit = (int) $Paging_or_limit;
            $select = 'SELECT ';
        }

        $sql = $select . ' *
                FROM ' . $this->table .'
                WHERE 1=1 '.$this->standard_restrictions() .'
                ORDER BY '. $datecol .' DESC ';


        if (is_object($Paging) && $Paging->enabled()){
            $limit  = ' LIMIT ' . $Paging->lower_bound() . ', ' . $Paging->per_page();
            $sql    .= $limit;
        }else{
            if ($limit!==false) {
                $sql .= ' LIMIT ' . $limit;
            }
        }


        $rows   = $this->db->get_rows($sql);

        if (is_object($Paging) && $Paging->enabled()){
            $sql    = "SELECT FOUND_ROWS() AS count";
            $total  = $this->db->get_value($sql);
            $Paging->set_total($total);
        }

        return $this->return_instances($rows);

    }

    public function create($data)
    {

        $newID  = $this->db->insert($this->table, $data);

        if ($newID) {
            $sql    = 'SELECT *
                        FROM ' . $this->table . '
                        WHERE ' .$this->pk . '='. $this->db->pdb($newID) .'
                        LIMIT 1';
            $result = $this->db->get_row($sql);

            if ($result) {
                return $this->return_instance($result);
            }
        }
    }

    public function return_instances($rows)
    {
        $row_count = PerchUtil::count($rows);
        if ($row_count > 0) {
            if (false && class_exists('SplFixedArray')) {
                $out = new SplFixedArray($row_count);
                for($i=0; $i<$row_count; $i++) {
                    $r = new $this->singular_classname($rows[$i]);
                    if (is_object($r) && $this->api) $r->api($this->api);
                    $out[$i] = $r;
                }
            }else{
                $out    = array();
                foreach($rows as $row) {
                    $r = new $this->singular_classname($row);
                    if (is_object($r) && $this->api) $r->api($this->api);
                    $out[] = $r;
                }
            }
            $row_count = null;
            return $out;
        }

        return false;
    }

    public function return_flattened_instances($rows)
    {        
        $row_count = PerchUtil::count($rows);
        if ($row_count > 0) {

            if (false && class_exists('SplFixedArray')) {
                $out    = new SplFixedArray($row_count);
                for($i=0; $i<=$row_count; $i++) {
                    $r = new $this->singular_classname($row);
                    if (is_object($r) && $this->api) $r->api($this->api);
                    $out[$i] = $r->to_array();
                }          
            }else{
                $out    = array();
                foreach($rows as $row) {
                    $r = new $this->singular_classname($row);
                    if (is_object($r) && $this->api) $r->api($this->api);
                    $out[] = $r->to_array();
                }
            }

            $row_count = null;
            return $out;
        }

        return false;
    }

    public function return_instance($row)
    {
        if (PerchUtil::count($row) > 0) {
            $r = new $this->singular_classname($row);
            if (is_object($r) && $this->api) $r->api($this->api);
            return $r;
        }

        return false;
    }

    public function return_flattened_instance($row)
    {
        if (PerchUtil::count($row) > 0) {
            $r = new $this->singular_classname($row);
            if (is_object($r) && $this->api) $r->api($this->api);
            return $r->to_array();
        }

        return false;
    }

    protected function standard_restrictions()
    {
        return '';
    }

    public function get_filtered_listing($opts, $where_callback=null, $pre_template_callback=null)
    {
        /*
            Are we using an index table? If so, filter differently.

         */
        if ($this->index_table!==false) {
            return $this->get_filtered_listing_from_index($opts, $where_callback, $pre_template_callback);
        }

        /*
            Otherwise, as you were.
         */


        $items       = array();
        $Item        = false;
        $single_mode = false;
        $where       = array();
        $order       = array();
        $limit       = '';

        // find specific _id
        if (isset($opts['_id'])) {
            $single_mode = true;
            $Item = $this->find($opts['_id']);
        }else{
            // if not picking an _id, check for a filter
            if (isset($opts['filter']) && (isset($opts['value']) || is_array($opts['filter']))) {

                // if it's not a multi-filter, make it look like one to unify what we're working with
                if (!is_array($opts['filter']) && isset($opts['value'])) {
                    $filters = array(
                                    array(
                                        'filter'     => $opts['filter'],
                                        'value'      => $opts['value'],
                                        'match'      => (isset($opts['match']) ? $opts['match'] : 'eq'),
                                        'match-type' => (isset($opts['match-type']) ? $opts['match-type'] : 'alpha')
                                    )
                                );
                    $filter_mode = 'AND';
                }else{
                    $filters = $opts['filter'];
                    $filter_mode = 'AND';

                    if (isset($opts['match']) && strtolower($opts['match'])=='or') {
                        $filter_mode = 'OR';
                    }
                }


                foreach($filters as $filter) {
                    $key = $filter['filter'];
                    $val = $filter['value'];
                    $raw_value = $filter['value'];
                    if (is_numeric($val)) $val = (float) $val;
                    $value     = $this->db->pdb($val);

                    $match = isset($filter['match']) ? $filter['match'] : 'eq';

                    switch ($match) {
                        case 'eq':
                        case 'is':
                        case 'exact':
                            $where[] = $key.'='.$value;
                            break;
                        case 'neq':
                        case 'ne':
                        case 'not':
                        case '!eq':
                            $where[] = $key.'!='.$value;
                            break;
                        case 'gt':
                            $where[] = $key.'>'.$value;
                            break;
                        case '!gt':
                            $where[] = $key.'!>'.$value;
                            break;
                        case 'gte':
                            $where[] = $key.'>='.$value;
                            break;
                        case '!gte':
                            $where[] = $key.'!>='.$value;
                            break;
                        case 'lt':
                            $where[] = $key.'<'.$value;
                            break;
                        case '!lt':
                            $where[] = $key.'!<'.$value;
                            break;
                        case 'lte':
                            $where[] = $key.'<='.$value;
                            break;
                        case '!lte':
                            $where[] = $key.'!<='.$value;
                            break;
                        case 'contains':
                            $v = str_replace('/', '\/', $raw_value);
                            $where[] = $key." REGEXP '[[:<:]]".$v."[[:>:]]'";
                            break;
                        case '!contains':
                            $v = str_replace('/', '\/', $raw_value);
                            $where[] = $key." NOT REGEXP '[[:<:]]".$v."[[:>:]]'";
                            break;
                        case 'regex':
                        case 'regexp':
                            $v = str_replace('/', '\/', $raw_value);
                            $where[] = $key." REGEXP '".$v."'";
                            break;
                        case 'between':
                        case 'betwixt':
                            $vals  = explode(',', $raw_value);
                            if (PerchUtil::count($vals)==2) {
                                $where[] = '(' . $key.'>'.trim($this->db->pdb($vals[0])) . ' AND ' . $key.'<'.trim($this->db->pdb($vals[1])).')';
                            }
                            break;
                        case '!between':
                        case '!betwixt':
                            $vals  = explode(',', $raw_value);
                            if (PerchUtil::count($vals)==2) {
                                $where[] = '(' . $key.'!>'.trim($this->db->pdb($vals[0])) . ' AND ' . $key.'!<'.trim($this->db->pdb($vals[1])).')';
                            }
                            break;
                        case 'eqbetween':
                        case 'eqbetwixt':
                            $vals  = explode(',', $raw_value);
                            if (PerchUtil::count($vals)==2) {
                                $where[] = '('.$key.'>='.trim($this->db->pdb($vals[0])). ' AND ' . $key.'<='.trim($this->db->pdb($vals[1])).')';
                            }
                            break;
                        case '!eqbetween':
                        case '!eqbetwixt':
                            $vals  = explode(',', $raw_value);
                            if (PerchUtil::count($vals)==2) {
                                $where[] = '('.$key.'!>='.trim($this->db->pdb($vals[0])). ' AND ' . $key.'!<='.trim($this->db->pdb($vals[1])).')';
                            }
                            break;
                        case 'in':
                        case 'within':
                            $vals  = explode(',', $raw_value);
                            $tmp = array();
                            if (PerchUtil::count($vals)) {
                                $where[] = $key.' IN ('.$this->db->implode_for_sql_in($vals).')';
                            }
                            break;
                        case '!in':
                        case '!within':
                            $vals  = explode(',', $raw_value);
                            $tmp = array();
                            if (PerchUtil::count($vals)) {
                                $where[] = $key.' NOT IN ('.$this->db->implode_for_sql_in($vals).')';
                            }
                            break;
                    }
                }
                $where = array(' ('.implode($where, ' '.$filter_mode.' ').') ');
            }
        }



        // sort
        if (isset($opts['sort'])) {
            $desc = false;
            if (isset($opts['sort-order']) && $opts['sort-order']=='DESC') {
                $desc = true;
            }else{
                $desc = false;
            }
            $order[] = $opts['sort'].' '.($desc ? 'DESC' : 'ASC');
        }else{
            $order[] = $this->default_sort_column . ' ' . $this->default_sort_direction;
        }

        if (isset($opts['sort-order']) && $opts['sort-order']=='RAND') {
            $order = array('RAND()');
        }

        // limit
        if (isset($opts['count'])) {
            $count = (int) $opts['count'];

            if (isset($opts['start'])) {
                $start = (((int) $opts['start'])-1). ',';
            }else{
                $start = '';
            }

            $limit = $start.$count;
        }

        if ($single_mode){
            $items = array($Item);
        }else{

            // Paging
            $Paging = new PerchPaging;

            if (isset($opts['pagination-var']) && $opts['pagination-var']!='') {
                $Paging->set_qs_param($opts['pagination-var']);
            }

            if ((!isset($count) || !$count) || (isset($opts['start']) && $opts['start']!='')) {
                $Paging->disable();
            }else{
                $Paging->set_per_page($count);
                if (isset($opts['start']) && $opts['start']!='') {
                    $Paging->set_start_position($opts['start']);
                }
            }

            $select  = $Paging->select_sql() . ' main.* ';
            $from    = 'FROM '.$this->table.' main ';

            if (is_callable($where_callback)) {

                // load up Query object
                $Query         = new PerchQuery();
                $Query->select = $select;
                $Query->from   = $from;
                $Query->where  = $where;

                // do callback
                $Query         = $where_callback($Query, $opts);

                // retrieve
                $select        = $Query->select;
                $from          = $Query->from;
                $where         = $Query->where;
            }

            $sql = $select.$from;

            $sql .= ' WHERE 1=1 ';

            if (count($where)) {
                $sql .= ' AND ' . implode(' AND ', $where);
            }

            if (count($order)) {
                $sql .= ' ORDER BY '.implode(', ', $order);
            }

            if ($Paging->enabled()) {
                $sql .= ' '.$Paging->limit_sql();
            }else{
                if ($limit!='') {
                    $sql .= ' LIMIT '.$limit;
                }
            }

            $rows    = $this->db->get_rows($sql);

            if ($Paging->enabled()) {
                $Paging->set_total($this->db->get_count($Paging->total_count_sql()));
            }

            // pre-template callback
            if (PerchUtil::count($rows) && $pre_template_callback && is_callable($pre_template_callback)) {
                $rows = $pre_template_callback($rows, $opts);
            }

            // each
            if (PerchUtil::count($rows) && isset($opts['each']) && is_callable($opts['each'])) {
                $content = array();
                foreach($rows as $item) {
                    $tmp = $opts['each']($item);
                    $content[] = $tmp;
                }
                $rows = $content;
            }

            $items  = $this->return_instances($rows);
        }


        // template

        if (is_callable($opts['template'])) {
            $callback = $opts['template'];
            $template = $callback($items);
        }else{
            $template = $opts['template'];
        }

        if (is_object($this->api)) {
            $Template = $this->api->get('Template');
            $Template->set($template,$this->namespace);
        }else{
            $Template = new PerchTemplate($template, $this->namespace);
        }



        $render_html = true;

        if (isset($opts['skip-template']) && $opts['skip-template']==true) {
            $render_html = false;
            if (isset($opts['return-html'])&& $opts['return-html']==true) {
                $render_html = true;
            }
        }

        if ($render_html) {
            if (isset($Paging) && $Paging->enabled()) {
                $paging_array = $Paging->to_array($opts);
                // merge in paging vars
                if (PerchUtil::count($items)) {
                    foreach($items as &$Item) {
                        foreach($paging_array as $key=>$val) {
                            $Item->squirrel($key, $val);
                        }
                    }
                }
            }


            if (PerchUtil::count($items)) {
                $html = $Template->render_group($items, true);
            }else{
                $Template->use_noresults();
                $html = $Template->render(array());
            }
        }


        if (isset($opts['skip-template']) && $opts['skip-template']==true) {

            if (isset($opts['api']) && $opts['api']==true) {
                $api = true;
            } else {
                $api = false;
            }

            if ($single_mode) {
                if ($api) {
                    return $Item->to_array_for_api();
                } else {
                    return $Item->to_array();
                }
            } 

            $processed_vars = array();
            if (PerchUtil::count($items)) {
                foreach($items as $Item) {
                    if ($api) {
                        $Item->prefix_vars = false;
                        $processed_vars[] = $Item->to_array_for_api();
                    } else {
                        $processed_vars[] = $Item->to_array();    
                    }
                }
            }

        
            if (PerchUtil::count($processed_vars)) {

                if ($api) {
                    $field_type_map = $Template->get_field_type_map($this->namespace);
                }

                $category_field_ids    = $Template->find_all_tag_ids('categories');

                foreach($processed_vars as &$item) {
                    if (PerchUtil::count($item)) {
                        foreach($item as $key => &$field) {

                            if ($api) {

                                if (array_key_exists($key, $field_type_map)) {
                                    $field = $field_type_map[$key]->get_api_value($field);
                                    continue;
                                }
                            } else {

                                if (in_array($key, $category_field_ids)) {
                                    $field = $this->_process_category_field($field);
                                }
                            }

                            if (is_array($field) && isset($field['processed'])) {
                                $field = $field['processed'];
                            }
                            if (is_array($field) && isset($field['_default'])) {
                                $field = $field['_default'];
                            }
                        }
                    }
                }
            }

            if (isset($opts['return-html'])&& $opts['return-html']==true) {
                $processed_vars['html'] = $html;
            }

            return $processed_vars;
        }

        if (strpos($html, '<perch:')!==false) {
            $Template = new PerchTemplate();
            $html        = $Template->apply_runtime_post_processing($html);
        }

        return $html;
    }

    public function get_filtered_listing_from_index($opts, $where_callback, $pre_template_callback=null)
    {
        $Perch = Perch::fetch();
        
        $index_table = PERCH_DB_PREFIX.$this->index_table;

        $where = array();

        $filter_mode = false;
        $single_mode = false;

        $content = array();

        // find specific _id
        if (isset($opts['_id'])) {
            $item_id = (int)$opts['_id'];
            $Paging = false;

            $sql = 'SELECT main.* FROM '.$this->table.' main WHERE main.'.$this->pk.'='.$this->db->pdb($item_id).' LIMIT 1 ';
            $rows = $this->db->get_rows($sql);
            $single_mode = true;
        }else{
            $sortval = ' idx2.indexValue as sortval ';

            if (isset($opts['paginate']) && $opts['paginate']) {
                if (isset($opts['pagination-var'])) {
                    $Paging = new PerchPaging($opts['pagination-var']);
                }else{
                    $Paging = new PerchPaging();
                }
                $sql = $Paging->select_sql();
            }else{
                $sql = 'SELECT';
            }

            $sql .= ' tbl.* FROM ( SELECT  idx.itemID, main.*, '.$sortval.' FROM '.$index_table.' idx
                            JOIN '.$this->table.' main ON idx.itemID=main.'.$this->pk.' AND idx.itemKey='.$this->db->pdb($this->pk).'
                            JOIN '.$index_table.' idx2 ON idx.itemID=idx2.itemID AND idx.itemKey='.$this->db->pdb($this->pk).' ';

            if (isset($opts['sort'])) {
                $sql .= ' AND idx2.indexKey='.$this->db->pdb($opts['sort']).' ';
            }else{
                $sql .= ' AND idx2.indexKey='.$this->db->pdb('_id').' ';
            }

            $where_clause =' idx.itemKey='.$this->db->pdb($this->pk).' ';

            // Categories
            if (isset($opts['category']) && !$this->bypass_categories) {
                $cats = $opts['category'];
                if (!is_array($cats)) $cats = array($cats);

                $match = 'any';
                if (isset($opts['category-match'])) {
                    $match = (strtolower($opts['category-match'])=='any' ? 'any' : 'all');
                }

                $pos = array();
                $neg = array();

                if (count($cats)) {
                    foreach($cats as $cat) {
                        if (substr($cat, 0, 1)=='!') {
                            $neg[] = substr($cat, 1);
                        }else{
                            $pos[] = $cat;
                        }
                    }                   

                    $sql .= $this->_get_filter_sub_sql('_category', $pos, false, $match, true, $where_clause);
                    $sql .= $this->_get_filter_sub_sql('_category', $neg, true, $match, true, $where_clause);
                }

            }

            // Tags
            if (isset($opts['tag']) && !$this->bypass_tags) {
                $cats = $opts['tag'];
                if (!is_array($cats)) $cats = array($cats);

                $match = 'any';
                if (isset($opts['tag-match'])) {
                    $match = (strtolower($opts['tag-match'])=='any' ? 'any' : 'all');
                }

                $pos = array();
                $neg = array();

                if (count($cats)) {
                    foreach($cats as $cat) {
                        if (substr($cat, 0, 1)=='!') {
                            $neg[] = substr($cat, 1);
                        }else{
                            $pos[] = $cat;
                        }
                    }

                    $sql .= $this->_get_filter_sub_sql('_tag', $pos, false, $match, false, $where_clause);
                    $sql .= $this->_get_filter_sub_sql('_tag', $neg, true, $match, false, $where_clause);
                }

            }

            // Runtime restrictions
            if (!$Perch->admin && count($this->runtime_restrictions)) {
                foreach($this->runtime_restrictions as $res) {
                    if (isset($opts['defeat-restrictions']) && $opts['defeat-restrictions'] && isset($res['defeatable']) && $res['defeatable']) {
                        // ... don't apply restriction as it's marked as defeatable and the options ask to defeat all defeatable restrictions
                    } else {
                        $sql .= $this->_get_filter_sub_sql($res['field'], $res['values'], $res['negative_match'], $res['match'], $res['fuzzy'], $where_clause);    
                    }
                }
            }


            // if not picking an _id, check for a filter
            if (isset($opts['filter']) && (isset($opts['value']) || is_array($opts['filter']))) {

                // if it's not a multi-filter, make it look like one to unify what we're working with
                if (!is_array($opts['filter']) && isset($opts['value'])) {
                    $filters = array(
                                    array(
                                        'filter'     => $opts['filter'],
                                        'value'      => $opts['value'],
                                        'match'      => (isset($opts['match']) ? $opts['match'] : 'eq'),
                                        'match-type' => (isset($opts['match-type']) ? $opts['match-type'] : 'alpha')
                                    )
                                );
                    $filter_mode = 'AND';
                }else{
                    $filters = $opts['filter'];
                    $filter_mode = 'AND';

                    if (isset($opts['match']) && strtolower($opts['match'])=='or') {
                        $filter_mode = 'OR';
                    }
                }


                $where = array();

                foreach($filters as $filter) {
                    $key = $filter['filter'];
                    $val = $filter['value'];
                    $match = isset($filter['match']) ? $filter['match'] : 'eq';

                    if (is_numeric($val)) $val = (float) $val;

                    switch ($match) {
                        case 'eq':
                        case 'is':
                        case 'exact':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue='.$this->db->pdb($val).')';
                            break;
                        case 'neq':
                        case 'ne':
                        case 'not':
                        case '!eq':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue != '.$this->db->pdb($val).')';
                            break;
                        case 'gt':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue > '.$this->db->pdb($val).')';
                            break;
                        case '!gt':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue !> '.$this->db->pdb($val).')';
                            break;
                        case 'gte':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue >= '.$this->db->pdb($val).')';
                            break;
                        case '!gte':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue !>= '.$this->db->pdb($val).')';
                            break;
                        case 'lt':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue < '.$this->db->pdb($val).')';
                            break;
                        case '!lt':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue !< '.$this->db->pdb($val).')';
                            break;
                        case 'lte':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue <= '.$this->db->pdb($val).')';
                            break;
                        case '!lte':
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue !<= '.$this->db->pdb($val).')';
                            break;
                        case 'contains':
                            $v = str_replace('/', '\/', $val);
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue REGEXP '.$this->db->pdb('[[:<:]]'.$v.'[[:>:]]').')';
                            break;
                        case 'notcontains':
                        case '!contains':
                            $v = str_replace('/', '\/', $val);
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue NOT REGEXP '.$this->db->pdb('[[:<:]]'.$v.'[[:>:]]').')';
                            break;
                        case 'regex':
                        case 'regexp':
                            $v = str_replace('/', '\/', $val);
                            $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue REGEXP '.$this->db->pdb($v).')';
                            break;
                        case 'between':
                        case 'betwixt':
                            $vals  = explode(',', $val);
                            if (PerchUtil::count($vals)==2) {

                                $vals[0] = trim($vals[0]);
                                $vals[1] = trim($vals[1]);

                                if (is_numeric($vals[0]) && is_numeric($vals[1])) {
                                    $vals[0] = (float)$vals[0];
                                    $vals[1] = (float)$vals[1];
                                }

                                $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND (idx.indexValue > '.$this->db->pdb($vals[0]).' AND idx.indexValue < '.$this->db->pdb($vals[1]).'))';
                            }
                            break;
                        case '!between':
                        case '!betwixt':
                            $vals  = explode(',', $val);
                            if (PerchUtil::count($vals)==2) {

                                $vals[0] = trim($vals[0]);
                                $vals[1] = trim($vals[1]);

                                if (is_numeric($vals[0]) && is_numeric($vals[1])) {
                                    $vals[0] = (float)$vals[0];
                                    $vals[1] = (float)$vals[1];
                                }

                                $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND (idx.indexValue !> '.$this->db->pdb($vals[0]).' AND idx.indexValue !< '.$this->db->pdb($vals[1]).'))';
                            }
                            break;
                        case 'eqbetween':
                        case 'eqbetwixt':
                            $vals  = explode(',', $val);
                            if (PerchUtil::count($vals)==2) {
                                $vals[0] = trim($vals[0]);
                                $vals[1] = trim($vals[1]);

                                if (is_numeric($vals[0]) && is_numeric($vals[1])) {
                                    $vals[0] = (float)$vals[0];
                                    $vals[1] = (float)$vals[1];
                                }

                                $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND (idx.indexValue >= '.$this->db->pdb($vals[0]).' AND idx.indexValue <= '.$this->db->pdb($vals[1]).'))';

                            }
                            break;
                        case '!eqbetween':
                        case '!eqbetwixt':
                            $vals  = explode(',', $val);
                            if (PerchUtil::count($vals)==2) {
                                $vals[0] = trim($vals[0]);
                                $vals[1] = trim($vals[1]);

                                if (is_numeric($vals[0]) && is_numeric($vals[1])) {
                                    $vals[0] = (float)$vals[0];
                                    $vals[1] = (float)$vals[1];
                                }

                                $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND (idx.indexValue !>= '.$this->db->pdb($vals[0]).' AND idx.indexValue !<= '.$this->db->pdb($vals[1]).'))';

                            }
                            break;
                        case 'in':
                        case 'within':
                            $vals  = explode(',', $val);
                            if (PerchUtil::count($vals)) {
                                $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue IN ('.$this->db->implode_for_sql_in($vals).'))';
                            }
                            break;
                        case '!in':
                        case '!within':
                            $vals  = explode(',', $val);
                            if (PerchUtil::count($vals)) {
                                $where[] = '(idx.indexKey='.$this->db->pdb($key).' AND idx.indexValue NOT IN ('.$this->db->implode_for_sql_in($vals).'))';
                            }
                            break;
                    }
                }

            }

            $sql .= ' WHERE 1=1 ';

            if (PerchUtil::count($where)) $sql .= ' AND ('.implode($where, ' OR ').') ';

            $sql .= ' AND idx.itemID=idx2.itemID AND idx.itemKey=idx2.itemKey ';


            if ((isset($opts['filter-mode']) && $opts['filter-mode']=='ungrouped')) {
                // don't do the GROUP BY.
                // The GROUP BY improves performance massively for large data sets, but in some circumstances is rolling up
                // too many results. This is the opposite of the old 'legacy-group' option
            } else {
                $sql .= 'GROUP BY idx.itemID, idx2.indexValue, '.$this->pk;  // DM added ', idx2.indexValue' for MySQL 5.7 compat  
            }           

            $sql .= ' ) as tbl ';

            $where = array();

            if (is_callable($where_callback)) {

                // load up Query object
                $Query         = new PerchQuery();
                $Query->select = $sql;
                $Query->where  = $where;

                // do callback
                $Query         = $where_callback($Query, $opts);

                // retrieve
                $sql           = $Query->select;
                $where         = $Query->where;
            }

            if (PerchUtil::count($where)) {
                $sql .= ' WHERE ('.implode($where, ' AND ').') ';
            }

            $sql .= 'GROUP BY itemID, sortval '; // DM added ', sortval' for MySQL 5.7 compat

            if ($filter_mode=='AND' && PerchUtil::count($filters)>1) {
                $sql .= ' HAVING count(*)='.PerchUtil::count($filters).' ';
            }

            // sort
            if (isset($opts['sort'])) {

                $direction = 'ASC';
                if (isset($opts['sort-order'])) {
                    switch($opts['sort-order']) {
                        case 'DESC':
                        case 'desc':
                            $direction = 'DESC';
                            break;

                        case 'RAND':
                        case 'rand':
                            $direction = 'RAND';
                            break;

                        default:
                            $direction = 'ASC';
                            break;
                    }
                }

                if ($direction=='RAND') {
                    $sql .= ' ORDER BY RAND()';
                }else{
                    if (isset($opts['sort-type']) && $opts['sort-type']=='numeric') {
                        $sql .= ' ORDER BY sortval * 1 '.$direction .' ';
                    }else{
                        $sql .= ' ORDER BY sortval '.$direction .' ';
                    }
                }

            }else{
                if (isset($opts['sort-type']) && $opts['sort-type']=='numeric') {
                    $sql .= ' ORDER BY sortval * 1 ASC ';
                }else{
                    $sql .= ' ORDER BY sortval ASC ';
                }
            }

            // Pagination
            if (isset($opts['paginate']) && $opts['paginate']) {

                if (is_object($opts['paginate'])) {

                    $Paging = $opts['paginate'];

                }else{

                    if (isset($opts['pagination-var'])) {
                        $Paging = new PerchPaging($opts['pagination-var']);
                    }else{
                        $Paging = new PerchPaging();
                    }

                    $Paging->set_per_page(isset($opts['count'])?(int)$opts['count']:10);
                }


                $opts['count'] = $Paging->per_page();
                $opts['start'] = $Paging->lower_bound()+1;

            }else{
                $Paging = false;
            }

            // limit
            if (isset($opts['count']) || isset($opts['start'])) {

                // count
                if (isset($opts['count'])) {
                    $count = (int) $opts['count'];
                }else{
                    $count = false;
                }

                // start
                if (isset($opts['start'])) {
                    $start = ((int) $opts['start'])-1;
                }else{
                    $start = 0;
                }

                if (is_object($Paging)) {
                    $sql .= $Paging->limit_sql();
                }else{
                    $sql .= ' LIMIT '.$start;
                    if ($count) $sql .= ', '.$count;
                }
            }

            $rows = $this->db->get_rows($sql);

            if (is_object($Paging)) {
                $total_count = $this->db->get_value($Paging->total_count_sql());
                $Paging->set_total($total_count);
            }

            // pre-template callback
            if (PerchUtil::count($rows) && $pre_template_callback && is_callable($pre_template_callback)) {
               $rows = $pre_template_callback($rows, $opts);
            }

            // each
            if (PerchUtil::count($rows) && isset($opts['each']) && is_callable($opts['each'])) {
                $content = array();
                foreach($rows as $item) {
                    $tmp = $opts['each']($item);
                    $content[] = $tmp;
                }
                $rows = $content;
            }

            $items = $this->return_instances($rows);

        }

        if (isset($opts['return-objects']) && $opts['return-objects']) {
            return $items;
        }


        $render_html = true;

        if (isset($opts['skip-template']) && $opts['skip-template']==true) {
            $render_html = false;
            if (isset($opts['return-html'])&& $opts['return-html']==true) {
                $render_html = true;
            }
        }


        // template
        if (is_callable($opts['template'])) {
            $callback = $opts['template'];
            $template = $callback($items);
        }else{
            $template = $opts['template'];
        }

        if (is_object($this->api)) {
            $Template = $this->api->get('Template');
            $Template->set($template,$this->namespace);
        }else{
            $Template = new PerchTemplate($template, $this->namespace);
        }


        if ($render_html) {


            if (isset($Paging) && is_object($Paging) && $Paging->enabled()) {
                $paging_array = $Paging->to_array($opts);
                // merge in paging vars
                if (PerchUtil::count($items)) {
                    foreach($items as &$Item) {
                        foreach($paging_array as $key=>$val) {
                            $Item->squirrel($key, $val);
                        }
                    }
                }
            }

            if (PerchUtil::count($items)) {

                if (isset($opts['split-items']) && $opts['split-items']) {
                    $html = $Template->render_group($items, false);
                }else{
                    $html = $Template->render_group($items, true);
                }

            }else{
                $Template->use_noresults();
                $html = $Template->render(array());
            }

        }


        if (isset($opts['skip-template']) && $opts['skip-template']==true) {

            if (isset($opts['api']) && $opts['api']==true) {
                $api = true;
            } else {
                $api = false;
            }

            if ($single_mode) {
                if ($api) {
                    return $Item->to_array_for_api();
                } else {
                    return $Item->to_array();      
                }
            } 

            $processed_vars = array();
            if (PerchUtil::count($items)) {
                foreach($items as $Item) {
                    if (isset($opts['api']) && $opts['api']) {
                        $Item->prefix_vars = false;
                        $processed_vars[] = $Item->to_array_for_api();
                    } else {
                        $processed_vars[] = $Item->to_array();    
                    }
                    
                }
            }

            if (PerchUtil::count($processed_vars)) {

                if ($api) {
                    $field_type_map = $Template->get_field_type_map($this->namespace);
                }

                $category_field_ids    = $Template->find_all_tag_ids('categories');
                //PerchUtil::debug($category_field_ids, 'notice');

                foreach($processed_vars as &$item) {
                    if (PerchUtil::count($item)) {
                        foreach($item as $key => &$field) {
                            
                            if ($api) {
                                if (array_key_exists($key, $field_type_map)) {
                                    $field = $field_type_map[$key]->get_api_value($field);
                                    continue;
                                }
                            } else {
                                if (in_array($key, $category_field_ids)) {
                                    $field = $this->_process_category_field($field);
                                }
                            }

                            if (is_array($field) && isset($field['processed'])) {
                                $field = $field['processed'];
                            }
                            if (is_array($field) && isset($field['_default'])) {
                                $field = $field['_default'];
                            }
                        }
                    }
                }
            }

            if (isset($opts['return-html'])&& $opts['return-html']==true) {
                $processed_vars['html'] = $html;
            }

            return $processed_vars;
        }

        if (is_array($html)) {
            // split-items
            if (PerchUtil::count($html)) {
                $Template = new PerchTemplate();
                foreach($html as &$html_item) {
                    if (strpos($html_item, '<perch:')!==false) {
                        $html_item = $Template->apply_runtime_post_processing($html_item);
                    }
                }
            }
        }else{
            if (strpos($html, '<perch:')!==false) {
                $Template = new PerchTemplate();
                $html     = $Template->apply_runtime_post_processing($html);
            }
        }

        return $html;
    }

    private function _get_filter_sub_sql($field, $items, $negative_match=false, $match, $fuzzy, $where_clause)
    {
        if (count($items)) {

            $index_table = PERCH_DB_PREFIX.$this->index_table;

            $cat_sql = 'SELECT DISTINCT idx.itemID FROM '.$index_table.' idx JOIN '.$this->table.' main ON idx.itemID=main.'.$this->pk.' AND '.$where_clause. ' AND ';

            $where = array();
            foreach($items as $item) {
                if ($fuzzy) {
                    $where[] = '(idx.indexKey='.$this->db->pdb($field).' AND idx.indexValue LIKE '.$this->db->pdb($item.'%').' OR idx.indexKey='.$this->db->pdb($field).' AND idx.indexValue='.$this->db->pdb($item).')';
                }else{
                    $where[] = '(idx.indexKey='.$this->db->pdb($field).' AND idx.indexValue='.$this->db->pdb($item).')';
                }

            }
            $cat_sql .= '(' . implode(' OR ', $where). ')';

            if ($match=='all') {
                $cat_sql .= ' GROUP BY idx.itemID HAVING COUNT(idx.itemID)='.count($items).' ';
            }
            $cat_results = $this->db->get_rows_flat($cat_sql);

            if (!PerchUtil::count($cat_results)) {
                $cat_results = array(null);
                if ($negative_match) return ''; // Return nothing if there are no categories to match against
            }

            if ($negative_match) {
                return ' AND idx.itemID NOT IN ('.$this->db->implode_for_sql_in($cat_results).') ';
            }

            return ' AND idx.itemID IN ('.$this->db->implode_for_sql_in($cat_results).') ';

        }

        return '';
    }

    private function _process_category_field($items)
    {
        if (PerchUtil::count($items)) {
            $out = array();

            if (!$this->cats_cache) {
                $Categories = $this->_get_Categories();
                $this->cats_cache = $Categories->get_cat_paths_by_id_runtime();
            }

            foreach($items as $catID) {
                $catID = (int)$catID;
                if (isset($this->cats_cache[$catID])) {
                    $out[] = $this->cats_cache[$catID];
                }
            }
            return $out;
        }
        return $items;
    }

    private function _get_Categories()
    {
        if (!$this->Categories) {
            $this->Categories = new PerchCategories_Categories();
        }

        return $this->Categories;
    }

}
