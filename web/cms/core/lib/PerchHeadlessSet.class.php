<?php

class PerchHeadlessSet
{
	public $name;
	public $length = 0;

	private $items = [];


	public function __construct($name)
	{
		$this->name = $name;
	}

	public function add_items($items)
	{
		if ($this->length > 0) {
			if (PerchUtil::count($items)) {
				foreach($items as $item) {
					$this->items[] = $item;
				}	
			}
		} else {
			$this->items = $items;
		}
		
		$this->length = count($this->items);
	}

	public function get_items()
	{
		if (PerchUtil::count($this->items)) {
			return $this->items;
		}
		return [];
	}
}