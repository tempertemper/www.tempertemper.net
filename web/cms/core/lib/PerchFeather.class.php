<?php

class PerchFeather
{
	public $path='';

	private $_components;

	function __construct($registered_components)
	{
		$this->_components = $registered_components;
		$this->path = PERCH_LOGINPATH.'/addons/feathers/'.strtolower(str_replace('PerchFeather_', '', get_class($this)));
	}


	public function get_css($opts, $index, $count) 
	{
		return false;
	}

	public function get_javascript($opts, $index, $count) 
	{
		return false;
	}

	protected function component_registered($str)
	{
		return in_array($str, $this->_components);
	}

	protected function register_component($str)
	{
		$this->_components[] = $str;
		return true;
	}

	public function get_components()
	{
		return $this->_components;
	}





	protected function _single_tag($tag=false, array $attrs)
	{
		if ($tag===false) return;

		return PerchXMLTag::create($tag, 'single', $attrs);
	}

	protected function _link_tag(array $attrs)
	{
		return PerchXMLTag::create('link', 'single', $attrs);
	}

	protected function _script_tag(array $attrs, $content='')
	{
		if (!isset($attrs['type'])) {
			$attrs['type'] = 'text/javascript';
		}

		$out = PerchXMLTag::create('script', 'opening', $attrs);
		$out .= $content;
		$out .= PerchXMLTag::create('script', 'closing');

		return $out;
	}

	protected function _conditional_comment($condition, $string)
	{
		return '<!--[if '.$condition.']>' . $string . '<![endif]-->';
	}
}
