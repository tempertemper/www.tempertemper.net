<?php

class PerchAssetFile
{
	private $details = array();

	public function __construct($attrs)
	{
		$this->details = $attrs;
	}

	public function __get($name)
	{
		if (isset($this->details[$name])) return $this->details[$name];
		return false;
	}

	public function to_array()
	{
		return $this->details;
	}
}