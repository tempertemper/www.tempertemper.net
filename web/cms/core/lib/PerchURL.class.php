<?php

class PerchURL
{
	private $scheme;
	private $secure       = false;
	private $path         = null;
	private $path_within_cp = null;
	private $domain       = null;
	private $query_string = '';
	private $query        = [];

	public function __construct($env, $get)
	{
		$this->scheme 		= $this->get($env, 'REQUEST_SCHEME');
		$this->secure       = false;
		$this->path         = parse_url($this->get($env, 'REQUEST_URI'), PHP_URL_PATH);
		$this->domain       = $this->get($env, 'HTTP_HOST');
		$this->query_string = '?'.$this->get($env, 'QUERY_STRING');
		$this->query        = $get;

		$this->path_within_cp = substr($this->path, strlen(PERCH_LOGINPATH));
	}

	public function __toString()
	{
		return $this->path.$this->query_string;
	}

	public function set_query($q)
	{
		$this->query = $q;
		$this->rebuild_query_string();
		return $this;
	}

	public function replace_in_query($q)
	{
		$this->query = array_merge($this->query, $q);
		$this->rebuild_query_string();
		return $this;
	}

	public function remove_from_query($key)
	{
		if (!is_array($key)) {
			$key = [$key];
		}

		if (count($key)) {
			foreach($key as $k) {
				if (isset($this->query[$k])) {
					unset($this->query[$k]);
					
				}
			}
			$this->rebuild_query_string();
		}
		
		return $this;
	}

	public function path_with_qs_within_cp()
	{
		return $this->path_within_cp.$this->query_string;
	}

	private function rebuild_query_string()
	{
		if (PerchUtil::count($this->query)) {
			$this->query_string = '?'. http_build_query($this->query);	
		} else {
			$this->query_string = '';
		}
	}

	private function get($env, $value) 
	{
		if (isset($env[$value])) {
			return $env[$value];
		}

		return null;
	}
}