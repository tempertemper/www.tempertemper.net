<?php

class PerchAPI_HeadlessFactory
{
	public function find($id)
	{
		$Factory = new $this->factory_class;
		$Item    = $Factory->find($id);

		if ($Item) {
			return new $this->singular_api_class($Item);
		}

		return null;
	}

	public function itemize()
	{
		$Factory = new $this->factory_class;
		$items   = $Factory->all();

		$out = [];

		if (PerchUtil::count($items)) {
			foreach($items as $Item) {
				$out[] = $Item->to_api_array();
			}
		}

		return $out;
	}
}