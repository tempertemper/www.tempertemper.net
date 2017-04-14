<?php
/*
	MySQL database access class.

	Uses PDO, but very deliberately not prepared statements.
	Prepared statements are compiled at the db server, not in PHP, so there's no way to get the 'real' query for debugging.
	As most Perch users don't have access to the MySQL query log, this makes it almost impossible for us to help people
	who are experiencing db problems.

	</trivia>

 */
class PerchDB_MySQL
{
	private $link     = false;
	public $errored   = false;
	public $error_msg = false;
	public $error_code = false;

	private $config   = [];

	public $dsn = '';

	static public $queries    = 0;


	function __construct($config=null)
	{
		if ($config) $this->config = $config;

		if (!defined('PERCH_DB_CHARSET')) 	define('PERCH_DB_CHARSET', 'utf8');
		if (!defined('PERCH_DB_PORT')) 		define('PERCH_DB_PORT', NULL);
		if (!defined('PERCH_DB_SOCKET')) 	define('PERCH_DB_SOCKET', NULL);
	}

	function __destruct()
	{
		$this->close_link();
	}

	private function config($item)
	{
		if (isset($this->config[$item])) return $this->config[$item];
		return constant($item);
	}

	private function open_link()
	{
		$dsn_opts = array();
		$dsn_opts['host'] 	= $this->config('PERCH_DB_SERVER');
		$dsn_opts['dbname'] = $this->config('PERCH_DB_DATABASE');

		if ($this->config('PERCH_DB_SOCKET')) $dsn_opts['unix_socket'] = $this->config('PERCH_DB_SOCKET');
		if ($this->config('PERCH_DB_PORT')) 	 $dsn_opts['port'] 	 	  = (int)$this->config('PERCH_DB_PORT');

		$dsn = 'mysql:';

		foreach($dsn_opts as $key=>$val) {
			$dsn .= "$key=$val;";
		}

		$this->dsn = $dsn;

		$opts = NULL;

		if ($this->config('PERCH_DB_CHARSET')) {
			// $opts = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '".$this->config('PERCH_DB_CHARSET')."'");
			// PHP bug means that this const isn't always defined. Useful.
			$opts = array(1002 => "SET NAMES '".$this->config('PERCH_DB_CHARSET')."'");
		}

		try {
			$this->link = new PDO($dsn, $this->config('PERCH_DB_USERNAME'), $this->config('PERCH_DB_PASSWORD'), $opts);
			if ($this->link) $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		}
		catch (PDOException $e) {

			switch($this->config('PERCH_ERROR_MODE'))
		    {
		        case 'SILENT':
		        	$this->errored = true;
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
			PerchUtil::debug($e->getMessage(), 'error');
			$this->error_msg = $e->getMessage();

			return false;
		}


	}

	private function close_link()
	{
		$this->link = null;
	}

	private function get_link()
	{
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

		try {
			$result = $link->exec($sql);
			self::$queries++;
		}
		catch (PDOException $e) {
			PerchUtil::debug("Invalid query: " . $e->getMessage(), 'error');
			$this->errored = true;
			$this->error_msg = $e->getMessage();
			return false;
		}

		if ($link->errorCode() && $link->errorCode()!='0000') {
			$err = $link->errorInfo();
			PerchUtil::debug("Invalid query: " . $err[2], 'error');
			$this->errored = true;
			$this->error_msg = $err[2];
			$this->error_code = $link->errorCode();
			return false;
		}
		$newid	= $link->lastInsertId();

		if (!$newid) {
		    self::$queries++;
			return $result;
		}

		return $newid;

	}


	public function get_rows($sql)
	{
		PerchUtil::debug($sql, 'db');
		$this->errored = false;

		$link = $this->get_link();
	    if (!$link) return false;

		try {
			$result = $link->query($sql);
			self::$queries++;
		}
		catch(PDOException $e) {
			PerchUtil::debug("Invalid query: " . $e->getMessage(), 'error');
			$this->errored = true;
			$this->error_msg = $e->getMessage();
			return false;
		}

		if ($link->errorCode() && $link->errorCode()!='0000') {
			$err = $link->errorInfo();
			PerchUtil::debug("Invalid query: " . $err[2], 'error');
			$this->errored = true;
			$this->error_msg = $err[2];
			return false;
		}

		if ($result->errorCode() && $result->errorCode()!='0000') {
			$err = $result->errorInfo();
			PerchUtil::debug("Invalid query: " . $err[2], 'error');
			$this->errored = true;
			$this->error_msg = $err[2];
			return false;
		}


		if ($result) {
			$r = $result->fetchAll(PDO::FETCH_ASSOC);
			$result = null;
			$count = PerchUtil::count($r);
			if ($count!==false) {
				if ($count===0) {
					PerchUtil::debug_badge('nil');
				}else{
					PerchUtil::debug_badge((string)$count);
				}
				return $r;
			}else{
				return false;
			}
		}

		return false;
	}

	public function get_rows_flat($sql)
	{
		PerchUtil::debug($sql, 'db');
		$this->errored = false;

		$link = $this->get_link();
	    if (!$link) return false;

		try {
			$result = $link->query($sql);
			self::$queries++;
		}
		catch(PDOException $e) {
			PerchUtil::debug("Invalid query: " . $e->getMessage(), 'error');
			$this->errored = true;
			$this->error_msg = $e->getMessage();
			return false;
		}

		if ($link->errorCode() && $link->errorCode()!='0000') {
			$err = $link->errorInfo();
			PerchUtil::debug("Invalid query: " . $err[2], 'error');
			$this->errored = true;
			$this->error_msg = $err[2];
			return false;
		}

		if ($result->errorCode() && $result->errorCode()!='0000') {
			$err = $result->errorInfo();
			PerchUtil::debug("Invalid query: " . $err[2], 'error');
			$this->errored = true;
			$this->error_msg = $err[2];
			return false;
		}

		if ($result) {
			$r = $result->fetchAll(PDO::FETCH_COLUMN, 0);
			$result = null;
			$count = PerchUtil::count($r);
			if ($count!==false) {
				PerchUtil::debug_badge((string)$count);
				return $r;
			}else{
				return false;
			}
		}

		return false;
	}



	public function get_row($sql)
	{
		PerchUtil::debug($sql, 'db');
		$this->errored = false;

		$link = $this->get_link();
	    if (!$link) return false;

		try {
			$result = $link->query($sql);
			self::$queries++;
		}
		catch(PDOException $e) {
			PerchUtil::debug("Invalid query: " . $e->getMessage(), 'error');
			$this->errored = true;
			$this->error_msg = $e->getMessage();
			return false;
		}

		if ($result) {
			$r = $result->fetch(PDO::FETCH_ASSOC);
			$result = null;

			$count = PerchUtil::count($r);
			if ($count!==false) {
				PerchUtil::debug_badge('1');
				return $r;
			}else{
				PerchUtil::debug_badge('0');
				return false;
			}

		}

		return false;
	}

	public function get_value($sql)
	{
		$result = $this->get_row($sql);

		if (is_array($result)) {
			PerchUtil::debug_badge('1');
			foreach($result as $val) {
				return $val;
			}
		}

		PerchUtil::debug_badge('0');
		return false;
	}

	public function get_count($sql)
	{
	    $result = $this->get_value($sql);
	    PerchUtil::debug_badge((string)$result);
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
			$value = PerchUtil::safe_stripslashes($value);
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
				$escape = $link->quote($value);
				break;
			case 'NULL':
				$escape = 'NULL';
				break;
			default:
				$escape = $link->quote($value);
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
			while ($i < $result->columnCount()) {
			    $r[] = $result->fetchColumn($i);
				$i++;
			}
			$result = NULL;
			return $r;
		}else{

			PerchUtil::debug("Invalid query: " . $link->error, 'error');
			return false;
		}

	}

	public function implode_for_sql_in($rows, $force_int=false)
    {
        foreach($rows as &$item) {
        	if ($force_int) $item = (int)$item;
            $item = $this->pdb($item);
        }

        return implode(', ', $rows);
    }


	public function get_client_info()
	{
		$link = $this->get_link();
		return $link->getAttribute(PDO::ATTR_CLIENT_VERSION);
	}

	public function get_server_info()
	{
		$link = $this->get_link();
		return $link->getAttribute(PDO::ATTR_SERVER_VERSION);
	}

	public function test_connection()
	{
		$link = $this->get_link();
		if ($link) return true;
		return false;
	}

}
