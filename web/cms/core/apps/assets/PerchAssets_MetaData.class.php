<?php

class PerchAssets_MetaData 
{
	private $tags = array();
	private $title = false;
   
    public function store_iptc($iptc)
    {
    	// tags
    	if (isset($iptc['2#025'])) {
    		$this->tags = $iptc['2#025'];
    	}

    	// title
    	if (isset($iptc['2#005']) && isset($iptc['2#005'][0])) {
    		$this->title = $iptc['2#005'][0];
    	}

    	return true;
    }

    public function set_title($title)
    {
    	$this->title = $title;
    }

    public function get_title()
    {
    	return $this->title;
    }

    public function get_tags()
    {
    	return $this->tags;
    }
}   