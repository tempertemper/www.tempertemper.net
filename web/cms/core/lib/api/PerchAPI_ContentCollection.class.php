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
		$default_opts = array(
			'skip-template' => true,
			'split-items'   => true,
			'filter'        => false,
			'paginate'      => false,
			'api'           => true,
        );

        if (is_array($opts)) {
            $opts = array_merge($default_opts, $opts);
        }else{
            $opts = $default_opts;
        }

		$key = $this->Collection->collectionKey();

		$Content = PerchContent::fetch();
		$result  = $Content->get_collection($key, $opts);

		return $result;
	}
}