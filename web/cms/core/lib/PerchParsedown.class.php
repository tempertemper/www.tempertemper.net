<?php

class PerchParsedown extends ParsedownExtra
{
	public function text($text)
	{
		// Fix markdown blockquote syntax - > gets encoded.
        if (substr($text, 0, 4)=='&gt;') $text = '>'.substr($text, 5);
        $text = preg_replace('/[\n\r]&gt;\s/', "\n> ", $text);

        // Fix autolink syntax
        $text = preg_replace('#&lt;(http[a-zA-Z0-9-\.\/:]*)&gt;#', "<$1>", $text);

		$text = parent::text($text);

		$text = $this->smartypants($text);

	    // Parsedown has naive encoding of URLs - fix it.
	    $text = str_replace('&amp;amp;', '&amp;', $text);

		return $text;
	}

    protected function inlineSpecialCharacter($Excerpt)
    {
        if ($Excerpt['text'][0] === '&' and ! preg_match('/^&#?\w+;/', $Excerpt['text']))
        {
            return array(
                'markup' => '&amp;',
                'extent' => 1,
            );
        }

        $SpecialCharacter = array('>' => 'gt', '<' => 'lt');

        if (isset($SpecialCharacter[$Excerpt['text'][0]]))
        {
            return array(
                'markup' => '&'.$SpecialCharacter[$Excerpt['text'][0]].';',
                'extent' => 1,
            );
        }
    }

    protected function de_entitize($text) 
    {
    	$ents = array('&#8220;', '&#8221;', '&#8216;',	'&#8217;', '&#8211;', 	'&#8212;');
    	$strs = array('“',		 '”',		'‘',		'’', 		'–', 		'—');

    	return str_replace($ents, $strs, $text);
    }

    public function smartypants($text)
    {
		if (!class_exists('\\Michelf\\SmartyPants', false) && class_exists('SmartyPants', true)) {
            // sneaky autoloading hack
        }

		$SmartyPants = new \Michelf\SmartyPants(\Michelf\SMARTYPANTS_ATTR_LONG_EM_DASH_SHORT_EN);
        $text = $SmartyPants->transform($text);
        
        if (PERCH_HTML_ENTITIES==false) {
            #$text = html_entity_decode($text, ENT_NOQUOTES, 'UTF-8');
            #$text = PerchUtil::html($text, -1);

            $text = $this->de_entitize($text);
        }

        return $text;
    }

    protected function blockFencedCodeComplete($Block)
    {
        $text = $Block['element']['text']['text'];

        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8', false);

        $Block['element']['text']['text'] = $text;

        return $Block;
    }

    protected function inlineCode($Excerpt)
    {
        $marker = $Excerpt['text'][0];

        if (preg_match('/^('.$marker.'+)[ ]*(.+?)[ ]*(?<!'.$marker.')\1(?!'.$marker.')/s', $Excerpt['text'], $matches))
        {
            $text = $matches[2];
            $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8', false);
            $text = preg_replace("/[ ]*\n/", ' ', $text);

            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'code',
                    'text' => $text,
                ),
            );
        }
    }

    protected function blockCodeComplete($Block)
    {
        $text = $Block['element']['text']['text'];

        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');

        $Block['element']['text']['text'] = $text;

        return $Block;
    }
}
