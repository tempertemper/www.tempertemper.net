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
    	if (file_exists($file)) {
    		unlink($file);
    	}
        parent::delete();
    }

}

?>