<?php

class PerchResourceBucket
{
	protected $name;
	protected $label;
	protected $type;
	protected $role;
	protected $web_path;
	protected $file_path;

	protected $error;

	protected $allow_non_uploads = false;

	public function __construct($details)
	{
		if (!isset($details['type'])) $details['type'] = 'file';
		
		$this->name      = $details['name'];
		$this->type      = $details['type'];
		$this->web_path  = $details['web_path'];
		$this->file_path = $details['file_path'];

		if (isset($details['label'])) {
			$this->label = $details['label'];
		} else {
			$this->label = ucwords($this->name);
		}

		if (isset($details['role'])) {
			$this->role = $details['role'];
		}
	}

	public function to_array()
	{
		return [
			'name'      => $this->name,
			'type'      => $this->type,
			'web_path'  => $this->web_path,
			'file_path' => $this->file_path,
			'remote'    => $this->is_remote(),
		];
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_label()
	{
		return $this->label;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function get_role()
	{
		return $this->role;
	}

	public function get_web_path()
	{
		return $this->web_path;
	}

	public function get_file_path()
	{
		return $this->file_path;
	}

	public function is_remote()
	{
		return $this->type!='file';
	}

	public function get_web_path_for_file($file)
	{
		return $this->get_web_path() .'/'.$file;
	}

	/**
	 * Is the bucket ready to be written to? Time to check permissions
	 * @return bool Ready?
	 */
	public function ready_to_write()
	{
		return is_writable($this->file_path);
	}

	public function write_file($file, $name)
	{
		$filename = PerchUtil::tidy_file_name($name);

		if (strpos($filename, '.php')!==false) 		$filename .= '.txt'; // diffuse PHP files
		if (strpos($filename, '.phtml')!==false) 	$filename .= '.txt'; // diffuse PHP files
		if ($filename == '.htaccess') 				$filename = 'htaccess.txt'; // apache overrides file
		if (strpos($filename, '.')===0) 			$filename = 'dot_'.substr($filename, 1).'.txt'; // hidden files

		$target = PerchUtil::file_path($this->file_path.'/'.$filename);

		if (file_exists($target)) {                                        
		    $dot = strrpos($filename, '.');
		    $filename_a = substr($filename, 0, $dot);
		    $filename_b = substr($filename, $dot);

		    $count = 1;
		    while (file_exists(PerchUtil::file_path($this->file_path.'/'.PerchUtil::tidy_file_name($filename_a.'-'.$count.$filename_b)))) {
		        $count++;
		    }

		    $filename   = PerchUtil::tidy_file_name($filename_a . '-' . $count . $filename_b);
		    $target     = PerchUtil::file_path($this->file_path.'/'.$filename);

		}

		if ($this->allow_non_uploads) {
			copy($file, $target);
			PerchUtil::set_file_permissions($target);
		} else {
			PerchUtil::move_uploaded_file($file, $target);	
		}
		

		return array(
				'name' => $filename,
				'path' => $target
			);
	}

	public function get_last_error()
	{
		return $this->error;
	}

	public function initialise()
	{
		if ($this->type == 'file') {
			if (!file_exists($this->get_file_path())) {
				$success = mkdir($this->get_file_path(), 0755, true);
				return $success;
			}
		}
	}

	public function get_files_with_prefix($prefix, $subpath=false)
	{
		$a  = array();	
	 
		try {
		    if ($dh = opendir($this->get_file_path())) {
		        while (($file = readdir($dh)) !== false) {
		            if(substr($file, 0, strlen($prefix))==$prefix) {
		                $a[] = $file;
		            }
		        }
		        closedir($dh);
		    }
		} catch (Exception $e) {
			PerchUtil::debug($e->getMessage(), 'error');
		}
		return $a;
	}

	public function enable_non_uploaded_files()
	{
		$this->allow_non_uploads = true;
	}

}