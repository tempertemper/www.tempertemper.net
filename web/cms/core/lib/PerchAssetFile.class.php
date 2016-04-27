<?php

class PerchAssetFile
{
	private $errors = array();

	private $details = array();


	public function __construct($attrs)
	{
		$this->details = $attrs;
	}

	public function __get($name)
	{
		if (isset($this->details[$name])) return $this->details[$name];
		return false;
	}

	public function to_array()
	{
		return $this->details;
	}

	public function get_errors()
	{
		return $this->errors;
	}

	public function get_mime_type()
	{
		return PerchUtil::get_mime_type($this->details['file_path']);
	}

	public function is_acceptable_upload($Tag, $default_accept_types)
	{
		// https://www.owasp.org/index.php/Unrestricted_File_Upload

		if (!$this->_check_file_name()) {
			$this->errors[] = 'Failed file name check';
			return false;
		}

		if (!$this->_check_file_size(0, $Tag->max_file_size())) {
			$this->errors[] = 'Failed file size check';
			return false;
		}
		
		if (!$this->_check_file_type($Tag, $default_accept_types)) {
			$this->errors[] = 'Failed file type check';
			return false;
		}

        return true;

	}

	private function _check_file_type($Tag, $default_accept_types)
	{
		// check the file type
        $accept_type_string = $default_accept_types;
        if ($Tag->accept()) {
            $accept_type_string = $Tag->accept();
        }

        $mime_type = $this->get_mime_type();

        if ($mime_type) {

            $filetypes = $this->_parse_filetypes_file();

            // break the accept string into parts
            if (strpos($accept_type_string, ',')) {
                $accept_types = explode(',', $accept_type_string);    
            } else {
                $accept_types = explode(' ', $accept_type_string);
            }

            if ($this->_mimetype_is_in_accepted_types($mime_type, $accept_types, $filetypes)) {

            	// check that file extension maps to a mime type that is accepted.
            	$extension = PerchUtil::file_extension($this->details['file_name']);

            	$mimetypes = $this->_parse_mimetypes_file($extension);

            	if (array_key_exists($extension, $mimetypes)) {

            		// we know the mime type for this filetype
            		if ($this->_mimetype_is_in_accepted_types($mimetypes[$extension], $accept_types, $filetypes)) {
            			return true;
            		}

            	}

            }

            
        }

        $this->errors[] = 'Mime type did not match: '.$mime_type;
        
        return false;

	}

	private function _mimetype_is_in_accepted_types($mime_type, $accept_types, $filetypes)
	{
		$parts = explode('/', $mime_type);
        $mime_type_wildcarded = $parts[0].'/*';

        if (PerchUtil::count($accept_types)) {

            foreach($accept_types as $type) {
                $type = trim($type);

                if (isset($filetypes[$type])) {
                    if (in_array($mime_type, $filetypes[$type]) || in_array($mime_type_wildcarded, $filetypes[$type])) {
                        return true;
                    }
                } else {
                	$this->errors[] = "Type '$type' does not appear in your config/filetypes.ini file";
                }
            }
        }
        return false;
	}

	private function _check_file_name()
	{
		// check file name
        $pattern = '#^[a-zA-Z0-9_\- ]{1,200}\.[a-zA-Z0-9]{1,10}$#';
        if (preg_match($pattern, $this->details['file_name'])) {
            return true;
        }

        $this->errors[] = 'File name did not match restricted pattern';
        return false;
	}

	private function _check_file_size($min_size=0, $max_size=null)
	{
		$file_size = $this->details['size'];

		// check file size
        if ($file_size > 0) {
            if ($max_size && $max_size>$min_size) {
                if ($file_size > $max_size) {
                   	$this->errors[] = 'Uploaded file is larger than declared max-file-size';
                    return false;
                }
            }
        } else {
            $this->errors[] = 'Uploaded file is 0 bytes';
            return false;
        }

        return true;
	}

	private function _parse_filetypes_file()
    {
        $file = PerchUtil::file_path(PERCH_PATH.'/config/filetypes.ini');
        if (!file_exists($file)) {
           	$this->errors[] = 'Missing filetypes.ini file!';
            return array();
        }

        $out = array();
        $contents = file_get_contents($file);
        if ($contents) {
            $lines = explode("\n", $contents);
            $key = 'undefined';
            foreach($lines as $line) {
                if (trim($line)=='') continue;

                if (strpos($line, '[')!==false) {
                    $key =  str_replace(array('[', ']'), '', trim($line));
                    continue;
                }

                if ($key) $out[$key][] = trim($line);
                $out['all'][] = trim($line);
            }
        }

        return $out;
    }

    private function _parse_mimetypes_file($limit_to_extension=null)
    {
    	$file = PerchUtil::file_path(PERCH_CORE.'/data/mime.types');
        if (!file_exists($file)) {
           	$this->errors[] = 'Missing mime.types file!';
            return array();
        }

        $data = file($file);

        $out = array();

        if (PerchUtil::count($data)) {
        	foreach($data as $line) {
        		$line = trim($line);

        		if (empty($line) || $line[0]=='#') {
        			continue;
        		}

        		if (strpos($line, "\t")===false) {
        			continue;
        		}

        		list($mime, $extensions) = preg_split('#' . "\t" . '+#', $line);

        		if (trim($extensions)==='') {
        			continue;
        		}

        		$extensions = explode(' ', $extensions);
        		if (PerchUtil::count($extensions)) {
        			foreach ($extensions as $extension) {
        				if ($limit_to_extension) {
        					if ($extension == $limit_to_extension) {
        						return array($extension=>$mime);
        					}
        				} else {
        					$out[$extension] = $mime;	
        				}
		                
		            }	
        		}
	            
        	}
        }
        return $out;
    }

}