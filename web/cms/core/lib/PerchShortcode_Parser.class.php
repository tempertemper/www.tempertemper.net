<?php

class PerchShortcode_Parser
{

	static $provider_map = null;

	public function parse($input, $Tag)
	{
		$shortcodes = $this->find_shortcodes($input);
		
		if (PerchUtil::count($shortcodes)) {

			$providers = $this->get_provider_map();

			foreach($shortcodes as $Shortcode) {
				if ($this->provider_exists($Shortcode->name)) {
					$input = $this->replace_shortcode($Shortcode, $input, $Tag);
				} else {
					$input = $this->remove_shortcode($Shortcode, $input);
				}
			}
		}

		return $input;
	}

	private function replace_shortcode($Shortcode, $str, $TemplateTag)
	{
		$Tag       = clone($TemplateTag);
		$providers = $this->get_provider_map();
		$Provider  = $providers[$Shortcode->name];

		$replacement = $Provider->get_shortcode_replacement($Shortcode, $Tag);

		return str_replace($Shortcode->tag, $replacement, $str);
	}

	private function remove_shortcode($Shortcode, $str)
	{
		$replacement = '';
		return str_replace($Shortcode->tag, $replacement, $str);
	}

	private function provider_exists($name)
	{
		return array_key_exists($name, $this->get_provider_map());
	}

	private function find_shortcodes($input)
	{
		$pattern = '#\[cms:(.*?)\]#';
		preg_match_all($pattern, $input, $matches, PREG_SET_ORDER);

		if ($matches) {
			$codes = [];
			foreach($matches as $match) {
				$codes[] = new PerchShortcode($match[0]);
			}
			return $codes;
		}

		return null;
	}

	private function get_provider_map()
	{
		if (self::$provider_map !== null) {
			return self::$provider_map;
		}

		$providers = PerchSystem::get_registered_shortcode_providers();

		if (PerchUtil::count($providers)) {

			$map = [];

			foreach($providers as $classname) {
				$Provider = new $classname;
				if (is_array($Provider->shortcodes)) {
					foreach($Provider->shortcodes as $shortcode) {
						$map[$shortcode] = $Provider;
					}
				}
			}

			self::$provider_map = $map;

			return $map;
		}

		self::$provider_map = [];
		return null;
	}

	
}
