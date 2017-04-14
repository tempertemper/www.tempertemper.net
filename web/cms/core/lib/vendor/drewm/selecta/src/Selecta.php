<?php

namespace DrewM\Selecta;
 
class Selecta
{
	public static $single_tags    = array('img', 'br', 'hr', 'input');
	public static $pseudo_classes = array('disabled', 'checked');
	public static $meta_map       = array('.'=>'class', '#'=>'id');

	public static function build($selector, $contents='', $open_tags=true, $close_tags=true)
	{
		$parts = self::break_into_parts($selector);
		
		if (count($parts)) {
			$parts = array_reverse($parts);
			foreach($parts as $part) {
				$contents = self::tagify($part, $contents, $open_tags, $close_tags);
			}
		}
		return $contents;
	}

	public static function wrap($selector, $contents='')
	{
		return self::build($selector, $contents, true, true);
	}

	public static function open($selector)
	{
		return self::build($selector, '', true, false);
	}

	public static function close($selector)
	{
		return self::build($selector, '', false, true);
	}

	private static function break_into_parts($selector)
	{
		$selector = self::sanitise_attribute_metas($selector);
		return explode(' ', $selector);
	}

	private static function tagify($selector='', $contents='', $open_tags=true, $close_tags=true)
	{
		$attrs = array();

		$metas   = '\.\#\[\:';
		$pattern = '/(['.$metas.'])([^'.$metas.']*)?/';
		preg_match_all($pattern, $selector, $matches, PREG_SET_ORDER);

		if (count($matches)) {
			foreach($matches as $match) {
				$attrs = self::build_attributes($match[1], $match[2], $attrs);
			}

			// reduce selector to just tag name
			$parts    = preg_split('/['.$metas.']/', $selector);
			$selector = $parts[0];
		}

		return self::build_tag($selector, $attrs, $contents, $open_tags, $close_tags);
	}

	private static function build_attributes($meta_char, $value, $attrs)
	{
		$key = false;
		if (isset(self::$meta_map[$meta_char])) {
			$key = self::$meta_map[$meta_char];
		}else{
			switch ($meta_char) {
				
				// Attribute selectors
				case '[':
					list($key, $value) = self::build_attribute_selector_attribute($value);
					break;

				// Pseudo-class selectors
				case ':':
					list($key, $value) = self::build_pseudo_class_attribute($value);
					break;
			}
		}

		if ($key){
			if (isset($attrs[$key])) {
				$attrs[$key] .= ' '.$value;
			}else{
				$attrs[$key] = $value;
			}
		}

		return $attrs;
	}

	private static function build_attribute_selector_attribute($value)
	{
		$value = rtrim($value, ']');

		if (strpos($value, '=')) {
			$parts = explode('=', $value, 2);
			$key   = $parts[0];
			$value = self::unsanitise_attribute_metas($parts[1]);
		}else{
			$key   = $value;
			$value = false;
		}

		return array($key, $value);
	}

	private static function build_pseudo_class_attribute($pclass='')
	{
		if (in_array($pclass, self::$pseudo_classes)) {
			return array($pclass, false);
		}

		return array(false, false);
	}

	private static function build_tag($name, $attributes=array(), $contents='', $open_tag=true, $close_tag=true)
	{
		$tag = '';

		if ($open_tag) {
			$tag = self::open_tag($name, $attributes);
		}
		
		if (in_array($name, self::$single_tags)) { 
			return $contents.$tag;
		}

		$tag .= $contents;

		if ($close_tag) {
			$tag .= self::close_tag($name);
		}

		return $tag;
	}

	private static function open_tag($name, $attributes=array())
	{
		$tag = '<'.self::html($name);
		if (count($attributes)) {
			// do attributes
			$attpairs = array();
			foreach($attributes as $key=>$val) {
				if ($val!='') {
					$attpairs[] = self::html($key).'="'.self::html($val, true).'"';
				}else{
					$attpairs[] = self::html($key);
				}
			}
			$tag .= ' '.implode(' ', $attpairs);
		}
		$tag .= '>';

		return $tag;
	}

	private static function close_tag($name)
	{
		return  '</'.self::html($name).'>';		
	}

	private static function html($s, $quotes=false, $double_encode=false)
	{	    
		if ($s || (is_string($s) && strlen($s))) {
			return htmlspecialchars($s, ($quotes?ENT_QUOTES:ENT_NOQUOTES), 'UTF-8', $double_encode);
		}
	    return '';
	}

	private static function sanitise_attribute_metas($selector)
	{
		if (strpos($selector, '[')!==false) {
			preg_match_all('/\[.*?\]/', $selector, $matches, PREG_SET_ORDER);
			if (count($matches)) {
				foreach($matches as $match) {
					$exact    = $match[0];
					$new      = str_replace(
									self::metas_from(), 
									self::metas_to(), 
								$exact);
					$selector = str_replace($exact, $new, $selector);
				}
			}
		}
		return $selector;
	}

	private static function unsanitise_attribute_metas($string)
	{
		return str_replace(self::metas_to(), self::metas_from(), $string);
	}

	private static function metas_from()
	{
		return array(
				'.', ':', '#', ' '
			);
	}

	private static function metas_to()
	{
		return array(
				'__DOT__', '__COLON__', '__HASH__', '__SPACE__'
			);
	}

} 