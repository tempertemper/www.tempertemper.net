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

		if (!defined('PERCH_SITE_BEHIND_LOGIN')) {
			define('PERCH_SITE_BEHIND_LOGIN', false);
		}

		if (!defined('PERCH_API_PATH')) {
			define('PERCH_API_PATH', '/api');
		}
	}

	public function get_route($url)
	{
		list($url, $query) = $this->clean_up_url($url);

		if ($url==='') $url = '/'; // homepage

		if (PERCH_SITE_BEHIND_LOGIN) {
			$Users       = new PerchUsers;
	        $CurrentUser = $Users->get_current_user();

	        if (!is_object($CurrentUser) || !$CurrentUser->logged_in()) {
	            return new PerchRoutedPage($url, $url, $query, false, 'errors/login-required.php', 403);
	        }
			
		}

		if (strpos($url, PERCH_API_PATH) === 0) {
			return new PerchRoutedPage($url, $url, $query);
		}

		$sql = 'SELECT p.pagePath, pr.routePattern, pr.routeRegExp, p.pageTemplate, pr.routeOrder
					FROM '.$this->table .' p LEFT JOIN '.PERCH_DB_PREFIX.'page_routes pr ON p.pageID=pr.pageID
					UNION SELECT NULL AS pagePath, pr2.routePattern, pr2.routeRegExp, pr2.templatePath AS pageTemplate, pr2.routeOrder
					FROM '.PERCH_DB_PREFIX.'page_routes pr2 WHERE templateID!=0 ORDER BY routeOrder ASC, pagePath ASC';


		$sql = 'SELECT p.pagePath, pr.routePattern, pr.routeRegExp, p.pageTemplate, pr.routeOrder, s.settingValue AS siteOffline
					FROM '.$this->table .' p LEFT JOIN '.PERCH_DB_PREFIX.'page_routes pr ON p.pageID=pr.pageID LEFT JOIN '.PERCH_DB_PREFIX.'settings s ON s.settingID=\'siteOffline\'
					UNION SELECT NULL AS pagePath, pr2.routePattern, pr2.routeRegExp, pr2.templatePath AS pageTemplate, pr2.routeOrder, NULL AS siteOffline
					FROM '.PERCH_DB_PREFIX.'page_routes pr2 WHERE templateID!=0 ORDER BY routeOrder ASC, pagePath ASC';

		$rows = $this->db->get_rows($sql);

		if (PerchUtil::count($rows)) {
			$patterns = [];
			foreach($rows as $row) {

				if ($row['siteOffline'] === '1') {
					return new PerchRoutedPage($url, $url, $query, false, 'errors/site-offline.php', 503);
				}

				if ($url == $row['pagePath']) {
					PerchUtil::debug('Matched page: '.$row['pagePath'].', so not using routes.', 'routing');
					return new PerchRoutedPage($url, $url, $query, false, $row['pageTemplate']);
				}
				if ($row['routeRegExp']!='') $patterns[] = $row;
			}
			if (count($patterns)) {
				foreach($patterns as $pattern) {
					if (preg_match('#'.$pattern['routeRegExp'].'#', $url, $match)) {
						if (PERCH_DEBUG) {
							if ($pattern['pagePath']=='') {
								PerchUtil::debug('Matched pageless route: '.PerchUtil::html($pattern['routePattern']));
							} else {
								PerchUtil::debug('Matched route: '.PerchUtil::html($pattern['routePattern']));
							}	
						}
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

		return '^/'.$url_pattern.'/?$';
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

		// multi-segment token?
		if (strpos($token, '/')) {
			$tokens = explode('/', $token);
		} else {
			$tokens = [$token];
		}

		$token_list = $this->get_tokens();

		$arr_token = [];

		foreach($tokens as $token) {
			if (isset($token_list[$token])) {
				$arr_token[] = $token_list[$token];
			} else {
				$arr_token[] = $token;
			}
		}

		$str_token = implode('/',$arr_token);

		if ($name) {
			return '(?<'.$name.'>'.$str_token.')';
		} else {
			return '('.$str_token.')';
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