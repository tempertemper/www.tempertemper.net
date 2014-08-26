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
                // Fix markdown blockquote syntax - > gets encoded.
                $value = preg_replace('/[\n\r]&gt;\s/', "\n> ", $value);
                
                // Fix autolink syntax
                $value = preg_replace('#&lt;(http[a-zA-Z0-9-\.\/:]*)&gt;#', "<$1>", $value);
                
                $Markdown = new ParsedownExtra();
                $value = $Markdown->text($value);

                if (!class_exists('\\Michelf\\SmartyPants', false) && class_exists('SmartyPants', true)) { 
                    // sneaky autoloading hack 
                }

                $SmartyPants = new \Michelf\SmartyPants;
                $value = $SmartyPants->transform($value);
                if (PERCH_HTML_ENTITIES==false) {
                    $value = html_entity_decode($value, ENT_NOQUOTES, 'UTF-8');    
                }

                break;
        }
        
        if (defined('PERCH_XHTML_MARKUP') && PERCH_XHTML_MARKUP==false) {
            $value = str_replace(' />', '>', $value);
        }

		
		return $value;
    }
}

?>