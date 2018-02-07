<?php

class PerchRepeater
{
	public $tags = array();

	private $details = array();

	function __construct($details=array())
	{
		$this->details = $details;
	}

	function __get($property) {
		if (isset($this->details[$property])) {
			return $this->details[$property];
		}
		return false;
	}

	function __call($method, $arguments=false)
	{    
        
		if (isset($this->details[$method])) {
			return $this->details[$method];
		}

		return false;
	}

	public function type()
	{
		return 'PerchRepeater';
	}

	public function set($key, $val)
	{
		$this->details[$key] = $val;

		return true;
	}

	public function get_index($items)
	{
		
		if (PerchUtil::count($items) && PerchUtil::count($this->tags)) {

			$index = array();

			foreach($this->tags as $Tag) {

				$FieldType = PerchFieldTypes::get($Tag->type(), false, $Tag);

				foreach($items as $item) {
					foreach($item as $key=>$val) {
						if ($key == $Tag->id()) {

							$field_index = $FieldType->get_index($val);

							if (PerchUtil::count($field_index)) {
								foreach($field_index as $field_index_item) {

									// don't deep index
									if (strpos($field_index_item['key'], '.')) continue;
									
									if ($key == $field_index_item['key']) {
										$indexing_key = $this->id().'.'.$key;
									}else{
										$indexing_key = $this->id().'.'.$key.'.'.$field_index_item['key'];
									}

									$index[] = array(
										'key'   => $indexing_key,
										'value' => $field_index_item['value']
									);
								}
							}
						}
					}
				}
			}

			return $index;

		}



		return false;
	}
}
