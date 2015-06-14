<?php

class PerchContent_PageTemplate extends PerchBase
{
    protected $table  = 'page_templates';
    protected $pk     = 'templateID';


    /**
     * Delete the template, along with its file
     * @return nothing
     */
    public function delete()
    {
    	$file = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/pages/'.$this->templatePath());
    	if (!PERCH_RUNWAY && file_exists($file)) {
    		unlink($file);
    	}
        parent::delete();
    }

    public function display_name() 
    {
        $out = '';

        if (strpos($this->templatePath(), '/')!==false) {
            $segments = explode('/', $this->templatePath());
            array_pop($segments);
            $out .= PerchUtil::filename(implode('/', $segments)).' → ';
        }

        $out .= $this->templateTitle();

        return $out;
    }
}
