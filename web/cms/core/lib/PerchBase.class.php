<?php

class PerchBase
{
    protected $db;
    protected $details;
    protected $table;

    protected $index_table          = false;
    protected $optimize_index       = true;

    protected $api                  = false;

    protected $event_prefix         = false;
    public    $suppress_events      = true;

    protected $can_log_resources    = true;

    protected $modified_date_column = false;

    protected $pk_is_int = true;
    protected $pk        = null;

    protected $exclude_from_api = [];

    public $prefix_vars  = true;

    public function __construct($details)
    {
        $this->db       = PerchDB::fetch();
        $this->details  = $details;

        $this->table    = PERCH_DB_PREFIX . $this->table;
    }

    public function __destruct()
    {
        $this->details = null;
    }

    public function __call($method, $arguments)
	{
        if (isset($this->details[$method])) {
            return $this->details[$method];
        }else if (array_key_exists($method, $this->details)) {
			return $this->details[$method];
		}else{
		    PerchUtil::debug('Looking up missing property ' . $method, 'notice');
            $array = $this->to_array();
            if (isset($array[$method])){
                return $array[$method];
            }
		    if (isset($this->details[$this->pk])){
		        $sql    = 'SELECT ' . $method . ' FROM ' . $this->table . ' WHERE ' . $this->pk . '='. $this->db->pdb($this->details[$this->pk]);
		        $this->details[$method] = $this->db->get_value($sql);
		        return $this->details[$method];
		    }
		}

		return false;
	}

    public function set_pk($col)
    {
        $this->pk = $col;
    }

    public function set_null_id()
    {
        $this->details[$this->pk] = null;
        $this->can_log_resources = false;
    }

    public function ready_to_log_resources()
    {
        return $this->can_log_resources;
    }

    public function to_array()
    {
        $out = $this->details;

        $dynamic_field_col = str_replace('ID', 'DynamicFields', $this->pk);
        if (isset($out[$dynamic_field_col]) && $out[$dynamic_field_col] != '') {
            $dynamic_fields = PerchUtil::json_safe_decode($out[$dynamic_field_col], true);
            if (PerchUtil::count($dynamic_fields)) {
                if ($this->prefix_vars) {
                    foreach($dynamic_fields as $key=>$value) {
                        $out['perch_'.$key] = $value;
                    }    
                }
                $out = array_merge($dynamic_fields, $out);
            }
        }

        return $out;
    }

    public function to_array_for_api()
    {
        // get current value
        $prefix_vars = $this->prefix_vars;
        // set to false
        $this->prefix_vars = false;

        // get content
        $out =  $this->to_array();
        
        // set back to old value
        $this->prefix_vars = $prefix_vars;

        $this->exclude_from_api[] = str_replace('ID', 'DynamicFields', $this->pk);

        foreach($this->exclude_from_api as $col) {
            if (array_key_exists($col, $out)) {
                unset($out[$col]);
            }
        }

        return $out;
    }

    public function id()
    {
        return $this->details[$this->pk];
    }

    public function update($data)
    {
        if ($this->modified_date_column) $data[$this->modified_date_column] = date('Y-m-d H:i:s');

        $r = $this->db->update($this->table, $data, $this->pk, $this->details[$this->pk]);
        $this->details = array_merge($this->details, $data);
        return $r;
    }

    public function delete()
    {
        $this->db->delete($this->table, $this->pk, $this->details[$this->pk]);
    }

    public function squirrel($key, $val)
    {
        // non-persistent store
        $this->details[$key] = $val;
    }

    public function set_details($details)
    {
        if (is_array($details)) {
            foreach($details as $key=>$val) {
                $this->details[$key]=$val;
            }

            $this->details[$this->pk] = $this->details[$this->pk];
        }
    }

    public function get_details()
    {
        return $this->details;
    }

    public function api($api=false)
    {
        if ($api!==false) {
            $this->api = $api;
        }

        return $this->api;
    }

    public function index($Template=null)
    {
        if (!$this->index_table) return;

        PerchUtil::mb_fallback();

        $table = PERCH_DB_PREFIX.$this->index_table;

        // clear out old items
        $sql = 'DELETE FROM '.$table.' WHERE itemKey='.$this->db->pdb($this->pk).' AND itemID='.$this->db->pdb($this->id());
        $this->db->execute($sql);

        $tags = $Template->find_all_tags_and_repeaters($Template->namespace);

        $tag_index = array();
        if (PerchUtil::count($tags)) {
            foreach($tags as $Tag) {
                if (!isset($tag_index[$Tag->id()])) {
                    $tag_index[$Tag->id()] = $Tag;
                }
            }
        }

        $fields = $this->to_array(array_keys($tag_index));

        $sql = 'INSERT INTO '.$table.' (itemKey, itemID, indexKey, indexValue) VALUES ';
        $values = array();

        $id_set = false;
        if (PerchUtil::count($fields)) {
            foreach($fields as $key=>$value) {

                if (strpos($key, 'DynamicFields')!==false || mb_substr($key, 0, 6)=='perch_' || mb_strpos($key, 'JSON')!==false) {
                    continue;
                }

                if (isset($tag_index[$key])) {
                    $tag = $tag_index[$key];
                }else{
                    $tag = new PerchXMLTag('<perch:x type="text" id="'.$key.'" />');
                }

                if ($tag->type()=='PerchRepeater') {
                    $index_value = $tag->get_index($value);
                }else{
                    $FieldType = PerchFieldTypes::get($tag->type(), false, $tag);
                    $index_value = $FieldType->get_index($value);
                }

                if (is_array($index_value)) {
                    foreach($index_value as $index_item) {
                        $data = array();
                        $data['itemKey']    = $this->db->pdb($this->pk);
                        $data['itemID']     = ($this->pk_is_int ? (int) $this->id() : $this->db->pdb($this->id()));
                        $data['indexKey']   = $this->db->pdb(mb_substr($index_item['key'], 0, 64));
                        $data['indexValue'] = $this->db->pdb(mb_substr($index_item['value'], 0, 255));

                        $values[] = '('.implode(',', $data).')';

                        if ($index_item['key']=='_id') $id_set = true;
                    }
                }
            }
        }

        // _id
        if (!$id_set) {
            $data = array();
            $data['itemKey']    = $this->db->pdb($this->pk);
            $data['itemID']     = ($this->pk_is_int ? (int) $this->id() : $this->db->pdb($this->id()));
            $data['indexKey']   = $this->db->pdb('_id');
            $data['indexValue'] = ($this->pk_is_int ? (int) $this->id() : $this->db->pdb($this->id()));

            $values[] = '('.implode(',', $data).')';
        }

        $sql .= implode(',', $values);
        $this->db->execute($sql);

        // optimize index
        if ($this->optimize_index) {
            $sql = 'OPTIMIZE TABLE '.$table;
            $this->db->get_row($sql);    
        }
        

        if ($this->event_prefix && !$this->suppress_events) {
            $Perch = Perch::fetch();
            $Perch->event($this->event_prefix.'.index', $this);
        }
        return true;
    }

}
