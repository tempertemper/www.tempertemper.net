<?php

class PerchAPI_HTTPClient
{
	private $timeout = 10;

	public function get($url, $params = false) 
	{
		if ($params) {
			$url .= '?' . http_build_query($params);
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}
}