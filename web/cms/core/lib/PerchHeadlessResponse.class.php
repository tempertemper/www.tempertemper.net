<?php

class PerchHeadlessResponse
{
	private $sets = [];
	private $status = 'ok';

    public function __construct()
    {
    }

    public function set_status($status='ok')
    {
    	$this->status = $status;
    }

    public function add_set($Set)
    {
        $this->sets[] = $Set;
    }

    public function respond()
    {
        header('Content-Type: application/json');
        $out = ['status' => $this->status];

        $out['sets'] = [];
        foreach($this->sets as $Set) {
            $out['sets'][$Set->name] = $Set->get_items();
        }

        if (PERCH_DEBUG) {
            $out['debug'] = PerchUtil::get_debug();
        }
 
        echo json_encode($out);
    }
}