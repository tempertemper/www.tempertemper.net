<?php

class PerchTemplateFilter 
{
	public $returns_markup = false;

	protected $Tag;
	protected $content;

	public function __construct($Tag, $content)
	{
		$this->Tag     = $Tag;
		$this->content = $content;
	}

	public function filterBeforeProcessing($value, $valueIsMarkup = false)
	{
		return $value;
	}

	public function filterAfterProcessing($value, $valueIsMarkup = false)
	{
		return $value;
	}
}