<?php

class PerchUtil
{
	static private $hold_redirects = false;

	static function count($a)
	{
		if (is_array($a)){
			return count($a);
		}else{
			return 0;
		}
	}

	static function debug($msg, $type='log', $encode=false)
	{
		$Perch  = Perch::fetch();

		if (!$Perch->debug){
			return false;
		}

		if ($encode || $type=='db') $msg = PerchUtil::html($msg);

		if (isset($msg) && (is_array($msg) || is_object($msg))){
			$msg	= '<pre>'.print_r($msg, 1).'</pre>';
		}

		$Perch->debug_items[] = array(
				'time' => microtime(true),
				'type' => $type,
				'msg'  => $msg,
				'caller' => PerchUtil::get_caller_info(),
			);
	}

	static function get_caller_info() {
	    $c = '';
	    $file = '';
	    $func = '';
	    $class = '';
	    $trace = debug_backtrace();
	    if (isset($trace[2])) {
	        $file = $trace[1]['file'];
	        $func = $trace[2]['function'];
	        if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
	            $func = '';
	        }
	    } else if (isset($trace[1])) {
	        $file = $trace[1]['file'];
	        $func = '';
	    }
	    if (isset($trace[3]['class'])) {
	        $class = $trace[3]['class'];
	        $func = $trace[3]['function'];
	        $file = $trace[2]['file'];
	    } else if (isset($trace[2]['class'])) {
	        $class = $trace[2]['class'];
	        $func = $trace[2]['function'];
	        $file = $trace[1]['file'];
	    }
	    if ($file != '') $file = basename($file);
	    $c = $file . ": ";
	    $c .= ($class != '') ? "" . $class . "->" : "";
	    $c .= ($func != '') ? $func . "(): " : "";
	    return($c);
	}

	public static function output_debug($return_value=false, $time=false)
	{
		$Perch  = Perch::fetch();

		if (!$Perch->debug){
			return false;
		}

		if ($Perch->debug == true){
			$out = '';
		    $err = error_get_last();
		    if ($err) PerchUtil::debug($err, 'error');

		    $messages = $Perch->debug_items;

		    $dev = false;
		    if (PERCH_PRODUCTION_MODE < PERCH_PRODUCTION) {
		    	$dev = true;
		    }

		    if(PerchUtil::count($messages)) {

		    	if ($time==false) $time = (isset($_SERVER['REQUEST_TIME_FLOAT']) ? $_SERVER['REQUEST_TIME_FLOAT'] : time());

		    	$prev_time = $messages[0]['time'];

		    	$out = '<table class="perch-debug">';
		    	$out .= ' <tr>';
		    	if ($dev) $out .= ' 	<th>Time</th>';
		    	if ($dev) $out .= ' 	<th>Δ</th>';
		    	$out .= ' 	<th>Debug Message</th>';
		    	$out .= ' </tr>';
		    	foreach($messages as $msg) {

		    		$out .= '<tr>';
		    		if ($dev) $out .= 	'<td>'.round($msg['time'] - $time, 4).'</td>';
		    		if ($dev) $out .= 	'<td>'.round($msg['time'] - $prev_time, 4).'</td>';
		    		$out .= 	'<td class="'.$msg['type'].'" title="'.$msg['caller'].'">'.$msg['msg'].'</td>';
		    		$out .= '</tr>';
		    		$prev_time = $msg['time'];
		    	}
		    	$out .= '</table>';
		    	$out .= '<link rel="stylesheet" href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/assets/css/debug.css" />';
		    }

	        if ($return_value) {
	            return $out;
	        }else{
	            echo $out;
	        }
		}
	}

	public static function mark($msg)
	{
		PerchUtil::debug(str_repeat('-', 30).' '.$msg.' '.str_repeat('-', 30), 'marker');
	}


	public static function html($s=false, $quotes=false, $double_encode=false)
	{
	    if ($quotes) {
	        $q = ENT_QUOTES;
	    }else{
	        $q = ENT_NOQUOTES;
	    }

		if ($s || (is_string($s) && strlen($s))) return htmlspecialchars($s, $q, 'UTF-8', $double_encode);
	    return '';
	}

	public static function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	public static function hold_redirects()
	{
		self::$hold_redirects = true;
		PerchUtil::debug('Holding redirects', 'success');
	}

	public static function release_redirects()
	{
		self::$hold_redirects = false;
	}

	public static function redirect($url)
	{
		if (!self::$hold_redirects) {
			PerchSession::close();
	    	header('Location: ' . $url);
	    	exit;
		}
	}

	public static function setcookie($name, $value = '', $expires = 0, $path = '', $domain = '', $secure = false, $http_only = false)
	{
	   header('Set-Cookie: ' . rawurlencode($name) . '=' . rawurlencode($value)
	                         . (empty($expires) ? '' : '; expires=' . gmdate('D, d-M-Y H:i:s \\G\\M\\T', $expires))
	                         . (empty($path)    ? '' : '; path=' . $path)
	                         . (empty($domain)  ? '' : '; domain=' . $domain)
	                         . (!$secure        ? '' : '; secure')
	                         . (!$http_only    ? '' : '; HttpOnly'), false);
	}

	public static function pad($n)
	{
	    $n = (int)$n;
		if ($n<10){
			return '0'.$n;
		}else{
			return ''.$n;
		}

	}

	public static function contains_bad_str($str)
	{
		$bad_strings = array(
			"content-type:"
			,"mime-version:"
			,"multipart/mixed"
			,"Content-Transfer-Encoding:"
			,"bcc:"
			,"cc:"
			,"to:"
		);

		foreach($bad_strings as $bad_string) {
		    if (stripos(strtolower($str), $bad_string) !== false) {
				return true;
			}
		}
	}

	public static function is_valid_email($email)
	{
        if (function_exists('filter_var')) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }else{

            if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    			return false;
    		}

    		// Split it into sections to make life easier
    		$email_array = explode("@", $email);
    		$local_array = explode(".", $email_array[0]);
    		for ($i = 0; $i < sizeof($local_array); $i++) {
    			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
    				return false;
    			}
    		}
    		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    			$domain_array = explode(".", $email_array[1]);
    			if (sizeof($domain_array) < 2) {
    				return false; // Not enough parts to domain
    			}
    			for ($i = 0; $i < sizeof($domain_array); $i++) {
    				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
    					return false;
    				}
    			}
    		}
    		return true;
        }
	}

	public static function send_email($to, $from_address, $from_name, $subject, $body, $misc_headers=false)
	{
		PerchUtil::debug('Deprecated: PerchUtil::send_email', 'error');
		$Perch  = Perch::fetch();

		$headers    = "From: ".$from_name." <".$from_address.">\r\n";

		if (!$misc_headers) $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

		if (defined('PERCH_MAIL_PARAMS')) {
		    $params = PERCH_MAIL_PARAMS;
		}else{
		    $params = false;
		}

		if ($misc_headers) $headers.=$misc_headers;

		if (is_array($to)) {
		    foreach($to as $mail_to) {
		        PerchUtil::debug("Sending mail '$subject' to '$mail_to' from '$from_name' ($from_address)");
		        @mail($mail_to, $subject, $body, $headers, $params);
		    }
		    return true;
		}else{
		    PerchUtil::debug("Sending mail '$subject' to '$to' from '$from_name' ($from_address)");
    		return @mail($to, $subject, $body, $headers, $params);
		}
	}

	public static function excerpt($str, $words, $strip_tags=true, $balance_tags=false, $append=false)
	{
	    $limit  = $words;
		$str 	= trim($str);
	    if ($strip_tags) $str = strip_tags($str);
        $aStr 	= explode(" ", $str);
		$newstr	= '';

		if (PerchUtil::count($aStr) <= $limit) {
			return $str;
		}

        for($i=0; $i < $limit; $i++) {
            $newstr.=$aStr[$i] . " ";
        }

        $newstr = trim($newstr);

        if ($append!=false) {
        	$newstr .= $append;
        }

        if ($balance_tags) return PerchUtil::balance_tags($newstr);

        return $newstr;
	}

	public static function excerpt_char($str, $chars, $strip_tags=true, $balance_tags=false, $append=false)
	{
	    $limit  = $chars;

	    $str 	= trim($str);
	    if ($strip_tags) $str = strip_tags($str);

	    if (strlen($str) <= $limit) return $str;

	    $str    = substr($str, 0, intval($limit));
	    $last_space = strrpos($str, ' ');
	    if ($last_space > 0) $str = substr($str, 0, $last_space);

	    if ($append!=false) {
        	$str .= $append;
        }

        if ($balance_tags) return PerchUtil::balance_tags($str);

	    return $str;
	}

	public static function balance_tags($str)
	{
	    // find broken tags
	    $regexp = '/<[^>]*$/';
	    preg_match($regexp, $str, $matches);
	    if (PerchUtil::count($matches)) {
	        // we have a broken tag
	        $last_lt = strrpos($str, '<');
	        if ($last_lt > 0) $str = substr($str, 0, $last_lt);
	    }

	    // find opening tags
	    $regexp = '/<([^\/]([a-zA-z]*))[^>]*>/';
	    preg_match_all($regexp, $str, $matches);
	    if (PerchUtil::count($matches)) {
	        $opening_tags = $matches[1];
	        $closing_tags = array();

	        $regexp = '/<\/([a-zA-z]*)>/';
    	    preg_match_all($regexp, $str, $matches);
    	    if (PerchUtil::count($matches)) {
	            $closing_tags = $matches[1];
    	    }

    	    // find closing tags for openers
    	    $opening_tags = array_reverse($opening_tags);
    	    foreach($opening_tags as $opening_tag) {
    	        if (isset($closing_tags[0])) {
    	            if ($closing_tags[0]!=$opening_tag) {
    	                $str .= '</'.$opening_tag.'>';
    	            }else{
    	                array_shift($closing_tags);
    	            }
    	        }else{
    	            $str .= '</'.$opening_tag.'>';
    	        }
    	    }
	    }

	    return $str;
	}

	/**
	 * Legacy Textile function. Do not use.
	 * @param  [type]  $string     [description]
	 * @param  boolean $strip_tags [description]
	 * @return [type]              [description]
	 */
	public static function text_to_html($string, $strip_tags=true)
	{
		PerchUtil::debug('Converting to Textile using deprecated PerchUtil::text_to_html', 'notice');

		if ($strip_tags) $string = strip_tags($string);

        if (!class_exists('\\Netcarver\\Textile\\Parser', false) && class_exists('Textile', true)) {
            // sneaky autoloading hack
        }

        if (PERCH_HTML5) {
            $Textile = new \Netcarver\Textile\Parser('html5');
        }else{
            $Textile = new \Netcarver\Textile\Parser;
        }


        if (PERCH_RWD) {
            $string  =  $Textile->setDimensionlessImages(true)->textileThis($string);
        }else{
            $string  =  $Textile->textileThis($string);
        }

        if (defined('PERCH_XHTML_MARKUP') && PERCH_XHTML_MARKUP==false) {
		    $string = str_replace(' />', '>', $string);
		}


		return $string;
	}

	public static function array_sort($arr_data, $str_column, $bln_desc=false)
    {
        $arr_data  = (array) $arr_data;

        if (PerchUtil::count($arr_data)) {

            $str_column     = (string) trim($str_column);
            $bln_desc       = (bool) $bln_desc;
            $str_sort_type  = ($bln_desc) ? SORT_DESC : SORT_ASC;

            foreach ($arr_data as $key => $row) {
                ${$str_column}[$key] = isset($row[$str_column]) ? $row[$str_column] : '';
            }
            array_multisort($$str_column, $str_sort_type, $arr_data);
        }

        return $arr_data;
    }

    public static function flip($odd_value, $flip=true)
    {
        global $perch_flip;

        if ($flip) {
            if ($perch_flip == true) {
                $perch_flip = false;
            }else{
                $perch_flip = true;
            }
        }

        if (!$perch_flip) return $odd_value;
    }

    public static function bool_val($str)
    {
        $str = strtolower($str);

        if ($str === 'false') return false;
        if ($str === '0') return false;
        if ($str === 0) return false;
        if ($str === 'no') return false;
        if ($str === 'n') return false;
        if ($str === false) return false;

        if ($str === 'true') return true;
        if ($str === '1') return true;
        if ($str === 1) return true;
        if ($str === 'y') return true;
        if ($str === 'yes') return true;
        if ($str === true) return true;

        return false;
    }

    public static function filename($filename, $include_crumb=true, $for_sorting=false)
    {
        $extensions = array('.html', '.htm', '.php');
        $filename = str_replace(array('.html', '.htm', '.php'), '', $filename);

        $filename = ltrim($filename, '/');
        $filename = str_replace(array('_', '-'), ' ', $filename);

        $parts = explode('/', $filename);
        foreach($parts as &$part) $part = ucfirst($part);

        $filename = array_pop($parts);

        if (strtolower($filename) == 'index') {
            if (count($parts)==0) {
                if ($for_sorting) {
                    $filename = '/';
                }else{
                    $filename = PerchLang::get('Home page');
                }

            }else{
                $filename = array_pop($parts);
            }

        }

        if ($include_crumb) {
            $parts[] = $filename;
            $filename = implode(' → ', $parts);
        }

        return $filename;
    }

	public static function in_section($section_path, $page_path)
	{
	    $parts = explode('/', $section_path);
	    array_pop($parts);
	    $section = implode('/', $parts);

	    if ($section == '') return false;


        $section_parts = explode('/', $section_path);
        $page_parts = explode('/', $page_path);


        for($i=0; $i<PerchUtil::count($section_parts); $i++) {
            if ($section_parts[$i] != $page_parts[$i]) {
                return $i-1;
            }
        }


	    return false;
	}

	public static function get_folder_depth($filename)
	{
        $parts = explode('.', strtolower($filename));
        array_pop($parts);
        $filename = implode('.', $parts);
        $filename = str_replace('/index', '', $filename);
	    $segments = explode('/', $filename);
	    return PerchUtil::count($segments)-1;
	}

    public static function json_safe_decode($json, $assoc=false)
    {
        return json_decode($json, $assoc);
    }

    public static function json_safe_encode($arr, $tidy=false)
    {
    	if ($tidy && defined('JSON_PRETTY_PRINT')){
    		return json_encode($arr, JSON_PRETTY_PRINT);
    	}
        return json_encode($arr);
    }

    public static function tidy_json($json)
    {
        $json = str_replace('{', "{\n\t", $json);
        $json = str_replace('",', '",'."\n\t", $json);
        $json = str_replace('}', "\n}", $json);
        return $json;
    }

    public static function tidy_file_name($filename)
    {
    	if (is_array($filename)) PerchUtil::debug($filename);

		$s	= strtolower($filename);
		$s	= str_replace('-', ' ', $s);
		$s	= preg_replace('/[^a-z0-9\s\.]/', '', $s);
		$s	= trim($s);
		$s	= preg_replace('/\s+/', '-', $s);

		if (strlen($s)>0){
			return $s;
		}else{
			$md5	= md5($filename);
			$s		= strtolower($md5);
			return 'ra-'.substr($s, 0, 4).'-'.substr($s, 5, 4);
		}
    }

    public static function get_dir_contents($dir, $include_dirs=true)
    {
        $Perch = Perch::fetch();

        $a = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if(substr($file, 0, 1) != '.' && !preg_match($Perch->ignore_pattern, $file)) {
                        if ($include_dirs || (!$include_dirs && !is_dir($dir.DIRECTORY_SEPARATOR.$file))) {
                            $a[] = $file;
                        }
                    }
                }
                closedir($dh);
            }
        }

        return $a;
    }

    public static function file_extension($file)
    {
    	if (strpos($file, '.')!==false) return substr($file, strrpos($file, '.')+1);
    	return false;
    }

    public static function strip_file_extension($file)
    {
        if (strpos($file, '.')===false) return $file;

        return substr($file, 0, strrpos($file, '.'));
    }

    /**
     * Remove the file name from the end of a path and return the path
     *
     * @param string $path
     * @return void
     * @author Drew McLellan
     */
    public static function strip_file_name($path)
    {
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($parts);
        return PerchUtil::file_path(implode('/', $parts));
    }

    public static function get_current_app()
    {
        $Perch = PerchAdmin::fetch();
        $page = $Perch->get_page();
        $apps = $Perch->get_apps();

        if (PerchUtil::count($apps)) {
            foreach($apps as $app) {
                if (strpos($page, $app['section'])!==false) {
                    return $app;
                }
            }
        }
        return false;
    }

    public static function urlify($string, $spacer='-')
    {
    	$string = trim($string);
    	$string = htmlspecialchars_decode($string, ENT_QUOTES);
    	$string = strip_tags($string);
    	$string = str_replace(array('$', '£', '€', '™'), array('', 'GBP ', 'EUR ', 'tm'), $string);
    	$string = preg_replace('#(\d)\.(\d)#', '$1 $2', $string); // make sure numbers with decimals don't mislead, e.g. 2.5 -> 25

    	$tranliterator_rule = 'Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();';

    	if (function_exists('transliterator_list_ids')) {
    		if (in_array('Latin-ASCII', transliterator_list_ids())) {
    			$tranliterator_rule = 'Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();';
    		}
    	}

    	if (function_exists('transliterator_transliterate')) {
    		$string = str_replace('-', ' ', $string);
    		$s = transliterator_transliterate($tranliterator_rule, $string);
    	}else if(class_exists('Transliterator')) {
    		$string = str_replace('-', ' ', $string);
    		$T = Transliterator::create($tranliterator_rule);
    		$s = $T->transliterate($string);
    	}else{
    		$s  = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    		$s  = strtolower($s);
    		$s  = preg_replace('/[^a-z0-9\-\s]/', '', $s);
    	}

        $s  = preg_replace('/[\s\-]+/', $spacer, $s);

        if (strlen($s)>0){
            return $s;
        }else{
            return PerchUtil::urlify_non_translit($string);
        }
    }

    public static function urlify_non_translit($string)
    {
        $s  = strtolower($string);
        $s  = preg_replace('/[^a-z0-9\s]/', '', $s);
        $s  = trim($s);
        $s  = preg_replace('/\s+/', '-', $s);

        if (strlen($s)>0){
            return $s;
        }else{
            $md5    = md5($string);
            $s      = strtolower($md5);
            return 'ra-'.substr($s, 0, 4).'-'.substr($s, 5, 4);
        }
    }

	public static function http_get_request($protocol, $host, $path)
	{
	    $url = $protocol . $host . $path;
	    PerchUtil::debug($url);
        $result = false;
        $use_curl = false;
        if (function_exists('curl_init')) $use_curl = true;

        if ($use_curl) {
            $ch 	= curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$result = curl_exec($ch);
			PerchUtil::debug($result);
			curl_close($ch);
        }else{
            if (function_exists('fsockopen')) {
                $fp = fsockopen($host, 80, $errno, $errstr, 10);
                if ($fp) {
                    $out = "GET $path HTTP/1.1\r\n";
                    $out .= "Host: $host\r\n";
                    $out .= "Connection: Close\r\n\r\n";

                    fwrite($fp, $out);
                    stream_set_timeout($fp, 10);
                    while (!feof($fp)) {
                        $result .=  fgets($fp, 128);
                    }
                    fclose($fp);
                }

                if ($result!='') {
                    $parts = preg_split('/[\n\r]{4}/', $result);
                    if (is_array($parts)) {
                        $result = $parts[1];
                    }
                }
            }
        }

        if ($result) return $result;

        return false;
	}

    public static function move_uploaded_file($filename, $destination)
    {
        $r = move_uploaded_file($filename, $destination);
        PerchUtil::set_file_permissions($destination);
        return $r;
    }

    public static function set_file_permissions($filename)
    {
        if (defined('PERCH_CHMOD_FILES'))
            @chmod($filename, PERCH_CHMOD_FILES);
    }

    /**
     * Make a file path OS-safe by swapping out the correct DIRECTORY_SEPARATOR
     *
     * @param string $path
     * @return string
     * @author Drew McLellan
     */
    public static function file_path($path)
    {
        if (DIRECTORY_SEPARATOR!='/') {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        }

        return $path;
    }

	public static function subnav($CurrentUser, $pages, $Lang=false)
	{
		$s = '';
		if (PerchUtil::count($pages)) {

			$Perch 	 = Perch::fetch();
			$section = $Perch->get_nav_page();

			$prefix  = '';

			if (strpos($section, 'addons')!==false) {
				$parts = explode('/', $section);

				while (count($parts) && array_shift($parts)!='apps') {};

				$prefix .= 'addons/apps/';
				$section = implode('/',$parts);
			}

			$s .= '<ul class="subnav">';

			foreach($pages as $page) {

				// Runway?
				if (isset($page['runway']) && $page['runway']==true && !PERCH_RUNWAY) {
					continue;
				}

				if ((isset($page['priv']) && $CurrentUser->has_priv($page['priv'])) || !isset($page['priv'])) {
					if (is_array($page['page'])) {
						$paths = $page['page'];
					}else{
						$paths = explode(',', $page['page']);
					}

					if ($Lang===false) {
						$label = PerchLang::get($page['label']);
					}else{
						$label = $Lang->get($page['label']);
					}


					$s .= '<li'. (in_array($section, $paths) ? ' class="selected"' : '').'><a href="'.PerchUtil::html(PERCH_LOGINPATH.'/'.$prefix.$paths[0].(strpos($paths[0],'?')?'':'/')).'">'.$label.'</a>';

					if (isset($page['badge']) && $page['badge']!='') {
						$s .= '<span class="badge">'.PerchUtil::html($page['badge']).'</span>';
					}


					$s .= '</li>';
				}

			}

			$s .= '</ul>';

		}

		return $s;
	}

	/**
	 * Create HTML for a smartbar filter. items should be array('arg'=>'', 'val'=>'', 'label'=>'')
	 *
	 * @package default
	 * @author Drew McLellan
	 */
	public static function smartbar_filter($id, $label, $selected_label, $items, $classname=false, $Alert=false, $alert_message=false, $clear_filter_url=false)
	{
		$s = '';

		if (!PerchUtil::count($items)) return $s;

			$str_items = '';
			$match = false;

			foreach($items as $item) {

				if (isset($_GET[$item['arg']]) && $_GET[$item['arg']]==$item['val']) {
					$match = $item['label'];
					if ($Alert) {
						if ($clear_filter_url!==false) {
							$clear_html = ' <a href="'.PerchUtil::html($clear_filter_url).'" class="action">'.PerchLang::get('Clear Filter').'</a>';
						}else{
							$clear_html = '';
						}

						if ($alert_message) {
							$Alert->set('filter', PerchLang::get($alert_message, $match).$clear_html);
						}else{
							$Alert->set('filter', PerchLang::get($selected_label, $match).$clear_html);
						}

					}
				}

				$str_items .= '<li>';
				$str_items .= '<a href="'.(isset($item['path'])?$item['path']:'').'?'.$item['arg'].'='.urlencode($item['val']).'">'.PerchUtil::html($item['label']).'</a>';
				$str_items .= '</li>';
			}

			if ($match){
				$s .= '<li class="filter filtered">';
			}else{
				$s .= '<li class="filter">';
			}

			if (isset($_GET['show-filter']) && ($_GET['show-filter']==$id)){
			 	$s .= '<ul class="open">';
			}else{
				$s .= '<ul>';
			}


			$s .= '<li>';
			$s .= '<a class="icon '.$classname.'" href="?show-filter='.$id.'">';
			if ($match) {
				$s .= PerchLang::get($selected_label, $match);
			}else{
				$s .= PerchLang::get($label);
			}

			$s .= '</a>';
			$s .= '</li>';

			$s .= $str_items;

			$s .= '</ul>';

		$s .= '</li>';

		return $s;
	}

	public static function table_dump($vars, $class='')
	{
		$out = '';

		if (PerchUtil::count($vars)) {
			$out .= '<table class="'.PerchUtil::html($class, true).'"><tr><th>ID</th><th>Value</th></tr>';
			foreach($vars as $key=>$val){
				$out .= '<tr><td><b>'.PerchUtil::html($key).'</b></td><td>';

				switch(gettype($val)) {
					case 'array':
						if (isset($val['processed'])) {
							$out .= $val['processed'];
						}else if(isset($val['_default'])){
							$out .= $val['_default'];
						}else{
							$out .= '<pre>'.print_r($val, true).'</pre>';
						}

						break;
					case 'object':
						$out .= '<pre>'.print_r($val, true).'</pre>';
						break;

					case 'boolean':
						$out .= ($val ? 'true' : 'false');
						break;

					default:
						if (strlen($val)>100) {
							$val = PerchUtil::excerpt_char($val, 100).'{...}';
						}
						$out .= $val;
				}


				$out .= '</td></tr>';

			}
			$out .= '</table>';
		}

		return $out;
	}


	public static function initialise_resource_bucket($bucket)
	{
		if (!file_exists($bucket['file_path'])) {
			$success = mkdir($bucket['file_path'], 0755, true);

			return $success;
		}
	}

	public static function is_assoc($array)
	{
  		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	public static function url_to_ssl($path)
	{
		if (strpos($path, 'https:')===0) {
			return $path;
		}

		if (strpos($path, 'http:')===0) {
			return str_replace('http:', 'https:', $path);
		}

		return 'https://'.$_SERVER['HTTP_HOST'].$path;
	}

	public static function url_to_non_ssl($path)
	{
		if (strpos($path, 'http:')===0) {
			return $path;
		}

		if (strpos($path, 'https:')===0) {
			return str_replace('https:', 'http:', $path);
		}

		return 'http://'.$_SERVER['HTTP_HOST'].$path;
	}

	public static function super_sort()
	{
		$args 	 = func_get_args();
		$to_sort = array_shift($args);

		usort($to_sort, PerchUtil::make_comparer($args));

		return $to_sort;
	}

	public static function make_comparer($criteria)
	{
	    // Normalize criteria up front so that the comparer finds everything tidy
	    //$criteria = func_get_args();
	    foreach ($criteria as $index => $criterion) {
	        $criteria[$index] = is_array($criterion)
	            ? array_pad($criterion, 3, null)
	            : array($criterion, SORT_ASC, null);
	    }

	    return function($first, $second) use (&$criteria) {
	        foreach ($criteria as $criterion) {
	            // How will we compare this round?
	            list($column, $sortOrder, $projection) = $criterion;
	            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

	            // If a projection was defined project the values now
	            if ($projection) {
	                $lhs = call_user_func($projection, $first[$column]);
	                $rhs = call_user_func($projection, $second[$column]);
	            }
	            else {
	                $lhs = $first[$column];
	                $rhs = $second[$column];
	            }

	            // Do the actual comparison; do not return if equal
	            if ($lhs < $rhs) {
	                return -1 * $sortOrder;
	            }
	            else if ($lhs > $rhs) {
	                return 1 * $sortOrder;
	            }
	        }

	        return 0; // tiebreakers exhausted, so $first == $second
	    };
	}

	public static function get_mime_type($file)
	{
	    if (!file_exists($file) || !is_readable($file)) return false;

	    $mimetype = false;

	    $use_finfo_class        = true;
	    $use_finfo_function     = true;
	    $use_getimagesize       = true;
	    $use_mime_content_type  = true;

	    if ($use_finfo_class && class_exists('finfo')) {
	        $finfo  = new finfo(FILEINFO_MIME, null);
	        $result = $finfo->file($file);

	        if ($result && strpos($result, ';')) {
	            $parts = explode(';', $result);
	            $mimetype = $parts[0];
	        }
	    }
	    if ($use_finfo_function && function_exists('finfo_open')) {
	        try {
	            $finfo  = finfo_open(FILEINFO_MIME, null);
	            $result = finfo_file($finfo, $file);
	            finfo_close($finfo);
	        } catch (Exception $e) {
	            // erm...
	            $result = false;
	        }

	        if ($result && strpos($result, ';')) {
	            $parts = explode(';', $result);
	            $mimetype = $parts[0];
	        }
	    }

	    if ($mimetype==false && $use_getimagesize && function_exists('getimagesize')) {
	        try {
	            $result = @getimagesize($file);
	            if (is_array($result)) $mimetype = $result['mime'];
	        } catch (Exception $e) {
	            $result = false;
	        }
	    }

	    if ($mimetype==false && $use_mime_content_type && function_exists('mime_content_type')) {
	        $mimetype = mime_content_type($file);
	    }

	    if ($mimetype==false && !stristr(ini_get("disable_functions"), "shell_exec")) {
	        $mimetype = shell_exec("file -bi " . escapeshellarg($file));
	    }

	    return $mimetype;
	}

	static function get($var, $default=false)
	{
	    if (isset($_GET[$var]) && $_GET[$var]!='') {
	        return $_GET[$var];
	    }

	    if (PERCH_RUNWAY && class_exists('PerchSystem')) {
	        $r = PerchSystem::get_url_var($var);
	        if ($r) return $r;
	    }

	    return $default;
	}

	static function post($var, $default=false)
	{
	    if (isset($_POST[$var]) && $_POST[$var]!='') {
	        return $_POST[$var];
	    }

	    return $default;
	}

	static function extend($default_opts, $opts)
	{
		if (is_array($opts)) {
		    $opts = array_merge($default_opts, $opts);
		}else{
		    $opts = $default_opts;
		}

		return $opts;
	}

	static function debug_error_handler($errno, $errstr, $errfile=false, $errline=false)
	{
		PerchUtil::debug('Error '.$errno.' in '.$errfile.' on line '.$errline, 'error');
		PerchUtil::debug($errstr, 'error');
	}

	static function get_client_ip()
	{
		$ipaddress = '';
	    if (array_key_exists('HTTP_CLIENT_IP', $_SERVER))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(array_key_exists('HTTP_X_REAL_IP', $_SERVER))
	        $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
	    else if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(array_key_exists('HTTP_X_FORWARDED', $_SERVER))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(array_key_exists('HTTP_FORWARDED_FOR', $_SERVER))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(array_key_exists('HTTP_FORWARDED', $_SERVER))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(array_key_exists('REMOTE_ADDR', $_SERVER))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	static function safe_stripslashes($str)
	{
		$strip = false;
		if (PERCH_STRIPSLASHES)			$strip = true;
		if (get_magic_quotes_gpc()) 	$strip = true;
		if (get_magic_quotes_runtime()) $strip = true;
		if ($strip) return stripslashes($str);
		return $str;
	}

	static function flush_output()
	{
		if (defined('PERCH_PROGRESSIVE_FLUSH') && PERCH_PROGRESSIVE_FLUSH) flush();
	}

}