<?php 

class PerchRoutedPage
{
	public $request_uri;
	public $path;
	public $args;
	public $template;
	public $http_status = 200;
	public $query;

	public function __construct($request_uri, $path, $query, $args=false, $template=false, $http_status=200)
	{
		$this->request_uri = $request_uri;
		$this->path        = $path;
		$this->query       = $query;
		$this->args        = $args;
		$this->http_status = $http_status;

		if ($http_status != 200) {
			$this->path = '/errors/'.$http_status;
		}

		if (trim($template)!='') {
			$this->template = PerchUtil::file_path(PERCH_TEMPLATE_PATH.'/pages/'.$template);
		}else{
			$this->template = PerchUtil::file_path(PERCH_SITEPATH.$path);
		}		

		if (PerchUtil::count($this->args)) {
			foreach($this->args as &$val) $val = rawurldecode($val);
		}
	}

	public function output_headers()
	{
		http_response_code($this->http_status);

		if (PERCH_PRODUCTION_MODE < PERCH_PRODUCTION) {
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Thu, 21 Feb 1980 06:53:00 GMT"); // Date in the past
		}
	}
}