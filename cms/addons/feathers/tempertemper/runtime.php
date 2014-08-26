<?php

PerchSystem::register_feather('tempertemper');

class PerchFeather_tempertemper extends PerchFeather
{
	public function get_css($opts, $index, $count)
	{
		$out = array();

		if (!$this->component_registered('style')) {
			$out[] = $this->_single_tag('link', array(
					'rel'=>'stylesheet',
					'href'=>$this->path.'/css/style.css'
				));
			$this->register_component('style');
		}

		return implode("\n\t", $out)."\n";
	}

	public function get_javascript($opts, $index, $count)
	{
		$out = array();

		if (!$this->component_registered('jquery')) {
			$out[] = $this->_script_tag(array(
				'src'=>$this->path.'/js/production.min.js'
			));
			$this->register_component('jquery');
		}

		return implode("\n\t", $out)."\n";
	}


}


?>