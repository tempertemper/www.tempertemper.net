<?php

class PerchPageRoute extends PerchBase
{
    protected $table        = 'page_routes';
    protected $pk           = 'routeID';
    protected $event_prefix = 'route';

    public function update($data)
    {
    	if (isset($data['routePattern'])) {
    		$Router = new PerchRouter();
    		$data['routeRegExp'] = $Router->pattern_to_regexp($data['routePattern']);
    	}

    	$r = parent::update($data);

    	$Perch = Perch::fetch();
    	$Perch->event('route.updated', $this);

    	return $r;
    }

}