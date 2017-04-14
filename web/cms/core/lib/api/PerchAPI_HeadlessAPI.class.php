<?php

class PerchAPI_HeadlessAPI
{

    public function new_response()
    {
        return new PerchHeadlessResponse();
    }

    public function new_set($name)
    {
    	return new PerchHeadlessSet($name);
    }
}