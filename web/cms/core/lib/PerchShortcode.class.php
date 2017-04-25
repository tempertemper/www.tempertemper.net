<?php

class PerchShortcode
{
	public $attributes = [];
    public $tag;
    public $name;

    public function __construct($tag)
    {
        $this->tag = $tag;
        $this->parse();
    }

    public function arg($key)
    {
    	if (array_key_exists($key, $this->attributes)) {
    		return $this->attributes[$key];
    	}

    	return null;
    }

    public function get_args()
    {
    	return $this->attributes;
    }

    private function parse()
    {
    	// turn the shortcode into an HTML tag and use the same attribute parsing rules
    	$tag = '<'.trim($this->tag, '[]').' />';
    	$tag = str_replace('<cms:', '<', $tag);
		list($this->name, $this->attributes) = $this->parse_tag($tag);
    }


    private function parse_tag($htmlortag) 
    {
		$p            = 0;
		$tag          = false;
		$inquote      = false;
		$started      = false;
		$stack        = '';
		$attrState    = -1;	// -1:NOTHING   1:NAME 2:VALUE
		$currentAttr  = false;
		$attrValuePos = -1;
		$attr         = [];

		$arg_index    = 0;

		while ($p < strlen($htmlortag)) {
			$c = substr($htmlortag, $p, 1);

			if ($c==' ' && $started && !$tag) {
				$tag   = $stack;
				$stack = '';

			} else if ($started && $c=='>' && ($attrState!=2 || $inquote==' ')) {		// END OF TAG (if not in a value, doesn't work without braces)
				$started = false;
				if ($attrState==1 && trim($stack)!='/')
					$attr[trim($stack)] = true;
				if ($attrState==2)
					$attr[$currentAttr] = $stack;
				break;	// DONE

			} else if ($started && $tag && $c=='=' && $attrState!=2) {					// END OF ATTR NAME, BEGIN OF VALUE
				$currentAttr = trim($stack);
				$stack       = '';
				$attrState   = 2;

			} else if ($started && $tag && $c==' ' && $attrState==1) {					// END OF ATTR NAME, BEGIN OF VALUE
				$currentAttr = trim($stack);
				$stack       = '';
				$attrState   = 5;
				
			} else if ($started && $tag && $attrState==5) {								// CHAR AFTER SPACE AFTER ATTR NAME, BEGIN OF ANOTHER ATTR
				$attr[$arg_index] = $currentAttr;
				$arg_index++;
				$currentAttr        = false;
				$stack              .= $c;
				$attrState          = 1;

			} else if (!$started && $c=='<') {											// BEGIN OF TAG
				$started = true;

			} else if ($started && $tag && $attrState==2 && $c===$inquote) {			// END OF VALUE
				$attr[$currentAttr] = $stack;
				$stack              = '';
				$attrState          = -1;
				$inquote            = false;
				$attrValuePos       = -1;
				
			} else if ($started && $tag && $attrState==2 && $attrValuePos == -1) {		// MIDDLE OF VALUE
				$attrValuePos = 0;
				if ($c=='\'') $inquote = '\'';
				else if ($c=='"') $inquote = '"';
				else {
					$inquote      = ' ';
					$stack        .= $c;
					$attrValuePos = 1;
				}

			} else if ($started && $tag && $attrState==-1) {							// BEGIN OF ATTR NAME
				$attrState = 1;
				$stack     .= $c;
				
			} else {
				$stack .= $c;
				if ($attrState==2) $attrValuePos++;

			}
			$p++;
		}

		return [$tag, $attr];
	}

}