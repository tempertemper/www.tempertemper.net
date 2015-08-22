<?php

class PerchRouter
{
	protected $db;
	protected $table = 'pages';

	public $default_tokens = [

		'*'       => '.*?',
		'i'       => '[0-9]+',
		'a'       => '[a-z]+',
		'slug'    => '[a-z0-9\-%\+]+',
		'year'    => '[1-2][0-9]{3}',
		'isodate' => '[1-2][0-9]{3}\-[0-9]{2}\-[0-3][0-9]',

	];

	public function __construct()
	{
		$this->db       = PerchDB::fetch();

		if (defined('PERCH_DB_PREFIX')) {
		    $this->table    = PERCH_DB_PREFIX.$this->table;
		}
	}

	public function get_route($url)
	{
		list($url, $query) = $this->clean_up_url($url);

		if ($url==='') $url = '/'; // homepage

		$sql  = 'SELECT p.pagePath, pr.routePattern, pr.routeRegExp, p.pageTemplate FROM '.$this->table .' p LEFT JOIN '.PERCH_DB_PREFIX.'page_routes pr
				ON p.pageID=pr.pageID ORDER BY pr.routeOrder ASC, p.pagePath ASC';

		$rows = $this->db->get_rows($sql);

		if (PerchUtil::count($rows)) {
			$patterns = [];
			foreach($rows as $row) {
				if ($url == $row['pagePath']) {
					PerchUtil::debug('Matched page: '.$row['pagePath'].', so not using routes.', 'routing');
					return new PerchRoutedPage($url, $url, $query, false, $row['pageTemplate']);
				}
				if ($row['routeRegExp']!='') $patterns[] = $row;
			}
			if (count($patterns)) {
				foreach($patterns as $pattern) {
					if (preg_match('#'.$pattern['routeRegExp'].'#', $url, $match)) {
						PerchUtil::debug('Matched route: '.PerchUtil::html($pattern['routePattern']));
						return new PerchRoutedPage($url, $pattern['pagePath'], $query, $match, $pattern['pageTemplate']);
					}
				}
			}
		}

		return new PerchRoutedPage($url, $url, $query, false, 'errors/404.php', 404);
	}

	public function pattern_to_regexp($url_pattern, $posix=false)
	{
		preg_match_all('#\[(.*?)\]#', $url_pattern, $matches, PREG_SET_ORDER);
		if (count($matches)){
			foreach($matches as $match) {
				if ($posix) {
					$url_pattern = str_replace($match[0], $this->token_to_posix($match[1]), $url_pattern);
				}else{
					$url_pattern = str_replace($match[0], $this->token_to_regexp($match[1]), $url_pattern);
				}

			}
			if ($posix) return '/'.$url_pattern.'/?$';

			return '^/'.$url_pattern.'/?$';
		}

		return $url_pattern;
	}

	private function token_to_regexp($token)
	{
		$name = false;

		if (strpos($token, ':')!==false) {
			$parts = explode(':', $token);
			$token = $parts[0];
			$name  = preg_quote($parts[1]);
		}

		// empty token?
		if ($token=='') $token = '*';

		$token_list = $this->get_tokens();

		if (isset($token_list[$token])) {

			if ($name) {
				return '(?<'.$name.'>'.$token_list[$token].')';
			}else{
				return '('.$token_list[$token].')';
			}
		}

		if ($name) {
			return '(?<'.$name.'>'.$token.')';
		}

		return $token;
	}

	private function token_to_posix($token)
	{
		$name = false;

		if (strpos($token, ':')!==false) {
			$parts = explode(':', $token);
			$token = $parts[0];
			$name  = preg_quote($parts[1]);
		}

		// empty token?
		if ($token=='') $token = '*';

		$token_list = $this->get_tokens();

		if (isset($token_list[$token])) {
			return '('.$token_list[$token].')';
		}

		return $token;
	}

	private function get_tokens()
	{
		$custom = PerchConfig::get('routing_tokens');

		if (is_array($custom)) {
			return array_merge($this->default_tokens, $custom);
		}

		return $this->default_tokens;
	}

	private function clean_up_url($url)
	{
		$parts = parse_url($url);
		$out = [];
		$out[] = rtrim($parts['path'], '/');

		// querystring
		if (isset($parts['query'])) {
			$out[] = $parts['query'];
		}else{
			$out[] = null;
		}

		return $out;
	}
}