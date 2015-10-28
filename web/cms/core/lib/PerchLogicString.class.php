<?php

class PerchLogicString
{
	private $exp;

	private $operators = array(
		' AND ' => ' && ',
		' OR '  => ' || ',
		' XOR ' => ' xor '
		);

	public function __construct($exp)
	{
		$exp = $this->_normalise_whitespace($exp);
		$exp = $this->_replace_operators($exp);
		$this->exp = $exp;
	}

	public function parse()
	{
		return filter_var($this->_parse_expression($this->exp), FILTER_VALIDATE_BOOLEAN);
	}

	private function _parse_expression($exp)
	{
		$safety = 0;

		// parse bracketed sections first
		while(mb_strpos($exp, '(')!==false) {
			$ob = mb_strpos($exp, '('); // open bracket
			$cb = mb_strpos($exp, ')'); // close bracket

			// move forward to find innermost nested bracket
			while(mb_strpos($exp, '(', $ob+1)!==false && mb_strpos($exp, '(', $ob+1) < $cb) {
				$ob = mb_strpos($exp, '(', $ob+1);
			}

			// parse bracketed section and reconstruct expression
			$exp = ($ob>0 ? mb_substr($exp, 0, $ob) : '') . $this->_parse_expression(mb_substr($exp, $ob+1, ($cb-$ob-1))) . mb_substr($exp, $cb+1);

			// guard against typos in input
			$safety++;
			if ($safety>100) break;
		}

		// break on (normalised) spaces; first 3 items should be value, operator, value
		$parts = explode(' ', $exp);

		while(count($parts)>2) {
			$args = array();
			$args[0] = filter_var(array_shift($parts), FILTER_VALIDATE_BOOLEAN);
			$op      = array_shift($parts);
			$args[1] = filter_var(array_shift($parts), FILTER_VALIDATE_BOOLEAN);

			// compare and push result back onto the front of the array, then repeat until done
			array_unshift($parts, $this->_compare_values($op, $args));
		}

		return $parts[0];
	}

	private function _compare_values($op, $args)
	{
		$r = false;

		switch($op) {
			case '&&':
				$r = ($args[0] && $args[1]);
				break;
			case '||':
				$r = ($args[0] || $args[1]);
				break;
			case 'xor':
				$r = ($args[0] xor $args[1]);
				break;
		}

		if ($r) return 'true';
		return 'false';
	}

	private function _normalise_whitespace($exp)
	{
		// multiple spaces to single space
		$exp = preg_replace('#\s+#', ' ', $exp);

		// remove space around brackets
		$exp = preg_replace('#\(\s+#', '(', $exp);
		$exp = preg_replace('#\s+\)#', ')', $exp);

		return $exp;
	}

	private function _replace_operators($exp)
	{
		return str_replace(array_keys($this->operators), array_values($this->operators), $exp);
	}

}