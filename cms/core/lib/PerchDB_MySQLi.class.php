<?php

class PerchDB_MySQLi
{
    private $link = false;
	public $errored   = false;
	public $error_msg = false;
	
	static public $queries    = 0;
	

	function __construct()
	{
		if (!defined('PERCH_DB_CHARSET')) 	define('PERCH_DB_CHARSET', NULL);
		if (!defined('PERCH_DB_PORT')) 		define('PERCH_DB_PORT', NULL);
		if (!defined('PERCH_DB_SOCKET')) 	define('PERCH_DB_SOCKET', NULL);
	}
    
	function __destruct() 
	{
		$this->close_link();
	}
		
	private function open_link() 
	{
		try {
			$this->link = new mysqli(PERCH_DB_SERVER, PERCH_DB_USERNAME, PERCH_DB_PASSWORD, PERCH_DB_DATABASE, PERCH_DB_PORT, PERCH_DB_SOCKET);
		} catch (Exception $e) {
			
		}

		if ($this->link->connect_errno) {
		    switch(PERCH_ERROR_MODE) 
		    {
		        case 'SILENT':
		            break;
		            
		        case 'ECHO':
		            if (!$this->errored) {
		                echo 'Could not connect to the database. Please check that the username and password are correct.';
		                $this->errored = true;
		            }
		            break;
		            
		        default:
		            PerchUtil::redirect(PERCH_LOGINPATH.'/core/error/db.php');
		            break;
		    }

			PerchUtil::debug("Could not create DB link!", 'error');
			return false;
		}

		if (PERCH_DB_CHARSET && !$this->link->set_charset(PERCH_DB_CHARSET)) {
		    PerchUtil::debug("Error loading character set utf8: ". $this->link->error, 'error');
		}
		
	}
	
	private function close_link() 
	{
		if ($this->link && $this->link->ping()) {
			$this->link->close();
			unset($this->link);
			$this->link  = false;
		}
	}
	
	private function get_link() 
	{
	    if ($this->link && !$this->link->ping()) {
            $this->link = false;
        }
	    
		if (!$this->link) {
			$this->open_link();
		}
		
		return $this->link;
	}
	
	public function execute($sql) 
	{
		PerchUtil::debug($sql, 'db');
		$this->errored = false;

		
		$link = $this->get_link();
	    if (!$link) return false;
		
		$result = $link->query($sql);
		self::$queries++;
		
		if ($link->errno) {
			PerchUtil::debug("Invalid query: " . $link->error, 'error');
			$this->errored = true;
			$this->error_msg = $link->error;
			return false;
		}
		
		$newid	= $link->insert_id;
		
		if (!$newid) {
		    self::$queries++;
			return $link->affected_rows;
		}
		
		return $newid;
		
	}
	
	
	public function get_rows($sql) 
	{
		
		PerchUtil::debug($sql, 'db');
		$this->errored = false;

		
		$link = $this->get_link();
	    if (!$link) return false;
		
		$result = $link->query($sql);
		self::$queries++;
		
		if ($result) {
			
			if ($result->num_rows > 0) {
				$r = array();
				while ($a = $result->fetch_assoc()) {
					$r[] = $a;
				}
			}else{
				$r = false;
			}
			$result->free();
			return $r;
			
		}else{
			PerchUtil::debug("Invalid query: " . $link->error, 'error');
			$this->errored = true;
			$this->error_msg = $link->error;
			return false;
		}
		
		
	}
	
	
	public function get_rows_flat($sql) 
	{
		
		PerchUtil::debug($sql, 'db');
		$this->errored = false;

		
		$link = $this->get_link();
	    if (!$link) return false;
		
		$result = $link->query($sql);
		self::$queries++;
		
		if ($result) {
			
			if ($result->num_rows > 0) {
				$r = array();
				while ($a = $result->fetch_array()) {
					$r[] = $a[0];
				}
			}else{
				$r = false;
			}
			$result->free();
			return $r;
			
		}else{
			PerchUtil::debug("Invalid query: " . $link->error, 'error');
			$this->errored = true;
			$this->error_msg = $link->error;
			return false;
		}
		
		
	}
	


	public function get_row($sql) 
	{
		PerchUtil::debug($sql, 'db');
		$this->errored = false;

		
		$link = $this->get_link();
	    if (!$link) return false;
		
		$result = $link->query($sql);
		self::$queries++;
		
		if ($result) {			
			if ($result->num_rows > 0) {
				$r	= $result->fetch_assoc();
			}else{
				$r = false;
			}
			$result->free();
			return $r;
			
		}else{
			
			PerchUtil::debug("Invalid query: " . $link->error, 'error');
			$this->errored = true;
			$this->error_msg = $link->error;
			return false;
		}
		
		
	}
	
	public function get_value($sql) 
	{
		
		$result = $this->get_row($sql);

		if (is_array($result)) {
			foreach($result as $val) {
				return $val;
			}
		}
		
		return false;
		
	}
	
	public function get_count($sql)
	{
	    $result = $this->get_value($sql);
	    return intval($result);
	}
	
	public function insert($table, $data, $ignore=false) 
	{
		
		$cols	= array();
		$vals	= array();
		
		foreach($data as $key => $value) {
			$cols[] = $key;
			$vals[] = $this->pdb($value);
		}
		
		$sql = 'INSERT'.($ignore?' IGNORE':'').' INTO ' . $table . '(' . implode(',', $cols) . ') VALUES(' . implode(',', $vals) . ')';
		
		return $this->execute($sql);
		
	}
	
	public function update($table, $data, $id_column, $id) 
	{
		
		$sql = 'UPDATE ' . $table . ' SET ';
		
		$items = array();
		
		foreach($data as $key => $value) {
			$items[] =  $key . '=' . $this->pdb($value);
		}
		
		$sql .= implode(', ', $items);
		
		$sql .= ' WHERE ' . $id_column . '=' . $this->pdb($id);
		
		return $this->execute($sql);
		
		
	}
	
	public function delete($table, $id_column, $id, $limit=false) 
	{
		
		$sql = 'DELETE FROM ' . $table . ' WHERE ' . $id_column . '=' . $this->pdb($id);
		
		if ($limit) {
			$sql .= ' LIMIT ' . $limit;
		}
		
		
		return $this->execute($sql);
		
	}
	
	
	public function pdb($value)
	{
		// Stripslashes
		if (get_magic_quotes_runtime()) {
			$value = stripslashes($value);
		}
		
		$link = $this->get_link();
	    if (!$link) return false;

		// Quote
		switch(gettype($value)) {
			case 'integer':
			case 'double':
				$escape = $value;
				break;
			case 'string':
				$escape = "'" . $link->escape_string($value) . "'";
				break;
			case 'NULL':
				$escape = 'NULL';
				break;
			default:
				$escape = "'" . $link->escape_string($value) . "'";
		}

		return $escape;
	}
	
	public function get_table_meta($table)
	{
		$sql	= 'SELECT * FROM ' . $table . ' LIMIT 1';
		
		$link = $this->get_link();

		$result = $link->query($sql);
		self::$queries++;
		
		if ($result) {			
			$r	= array();
			$i 	= 0;
			while ($i < $result->field_count) {
			    $r[] = $result->fetch_field($i);
				$i++;
			}
			$result->free();
			return $r;
		}else{
			
			PerchUtil::debug("Invalid query: " . $link->error, 'error');
			return false;
		}
		
	}
	
	public function implode_for_sql_in($rows)
    {
        foreach($rows as &$item) {
            $item = $this->pdb($item);
        }
        
        return implode(', ', $rows);
    }
	
	public function get_client_info()
	{
		$link = $this->get_link();
		return $link->client_info;
	}

	public function get_server_info()
	{
		$link = $this->get_link();
		return $link->server_info;
	}
	
}

?>