<?php

class PerchAPI_Text
{
    public $app_id = false;
    public $version = 1.0;

    private $Lang = false;

    function __construct($version=1.0, $app_id, $Lang)
    {
        $this->app_id = $app_id;
        $this->version = $version;

        $this->Lang = $Lang;
    }

    public function text_to_html($value)
    {
        switch(PERCH_APPS_EDITOR_MARKUP_LANGUAGE) {
            case 'textile' :
                if (!class_exists('\\Netcarver\\Textile\\Parser', false) && class_exists('Textile', true)) {
                    // sneaky autoloading hack
                }

                if (PERCH_HTML5) {
                    $Textile = new \Netcarver\Textile\Parser('html5');
                }else{
                    $Textile = new \Netcarver\Textile\Parser;
                }

                if (PERCH_RWD) {
                    $value  =  $Textile->setDimensionlessImages(true)->textileThis($value);
                }else{
                    $value  =  $Textile->textileThis($value);
                }

                break;

            case 'markdown' :
 
                $Markdown = new PerchParsedown();
                $value = $Markdown->text($value);

                break;
        }

        if (defined('PERCH_XHTML_MARKUP') && PERCH_XHTML_MARKUP==false) {
            $value = str_replace(' />', '>', $value);
        }


		return $value;
    }
}
