<?php

class PerchGetter
{
	private $val     = null;
	private $param   = '';
	private $default = '';

	private $prefix  = '';
	private $suffix  = '';

	public function __construct($param, $default=null)
	{
		$this->param   = $param;
		$this->default = $default;
		$this->val     = $default;

		$this->populate();
	}

	public function __toString()
	{
		$val = $this->val;

		$val = $this->prefix.$val;
		$val = $val.$this->suffix;

		return $val;
	}

	public function asInt()
	{
		return (int) $this->__toString();
	}

	public function populate()
	{
		if (isset($_GET[$this->param]) && $_GET[$this->param]!='') {
		    $this->val = rawurldecode($_GET[$this->param]);
		}

		if (PERCH_RUNWAY) {
		    $r = PerchSystem::get_url_var($this->param);
		    if ($r) $this->val = $r;
		}
	}

	public function withPrefix($with)
	{
		$this->prefix = $with;
		return $this;
	}

	public function withSuffix($with)
	{
		$this->suffix = $with;
		return $this;
	}
}