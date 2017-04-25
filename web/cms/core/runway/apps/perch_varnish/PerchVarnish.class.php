<?php

class PerchVarnish extends PerchApp
{

	public function purge(PerchContent_Page $Page)
	{
		$Settings = $this->api->get('Settings');
		$siteURL = $Settings->get('siteURL')->val();

		$url = parse_url($siteURL);
		if (!isset($url['protocol'])) {
			$siteURL = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
		}
		$siteURL = rtrim($siteURL, '/');

		$this->issue_purge_request($siteURL.$Page->pagePath());

		$Routes = new PerchPageRoutes();
		$routes = $Routes->get_routes_for_page($Page->id());
		if (PerchUtil::count($routes)) {
			$Router = new PerchRouter;
			foreach($routes as $Route) {
				if (substr($Route->routeRegExp(), 0, 1)!='^') {
					$this->issue_purge_request($siteURL.'/'.$Route->routePattern());
				}else{
					$posix = $Router->pattern_to_regexp($Route->routePattern(), true);
					$this->issue_ban_request($siteURL, $posix);
				}
			}
		}
	}

	private function issue_purge_request($url)
	{
		PerchUtil::debug('Varnish purging page: '.$url);
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PURGE");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ch);
			curl_close($ch);
		}catch(Exception $e) {
			PerchUtil::debug($e->getMessage(), 'error');
			return false;
		}

		return true;
	}

	private function issue_ban_request($url, $pattern)
	{
		PerchUtil::debug('Varnish banning page: '.$url.$pattern);
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url.$pattern);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "BAN");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ch);
			curl_close($ch);
		}catch(Exception $e) {
			PerchUtil::debug($e->getMessage(), 'error');
			return false;
		}

		return true;
	}

}