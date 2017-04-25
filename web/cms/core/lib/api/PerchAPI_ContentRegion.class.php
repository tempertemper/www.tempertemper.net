<?php

class PerchAPI_ContentCollection
{	
	private $Collection;

	public function __construct(PerchContent_Collection $Collection)
	{
		$this->Collection = $Collection;
	}

	public function query($opts)
	{
		$key = $this->Collection->collectionKey();

		$opts['skip-template'] = true;

		$Content = PerchContent::fetch();
		$result  = $Content->get_collection($key, $opts);

		return $result;
	}
}