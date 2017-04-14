<?php

class PerchAPI_ContentCollections extends PerchAPI_HeadlessFactory
{
	protected $factory_class = 'PerchContent_Collections';
	protected $singular_api_class = 'PerchAPI_ContentCollection';

	public function find($collectionKey)
	{
		$Collections = new $this->factory_class;
		$Collection  = $Collections->get_one_by('collectionKey', $collectionKey);

		if ($Collection) {
			return new $this->singular_api_class($Collection);
		}

		return null;
	}
	
}