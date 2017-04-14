<?php

class PerchAPI_ContentRegions extends PerchAPI_HeadlessFactory
{
	protected $factory_class = 'PerchContent_Regions';
	protected $singular_api_class = 'PerchAPI_ContentRegion';


	public function query($key, $opts)
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

        $Content = PerchContent::fetch();
        return $Content->get_custom($key, $opts);
	}
}