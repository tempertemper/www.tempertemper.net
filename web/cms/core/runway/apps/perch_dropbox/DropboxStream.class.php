<?php

use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;

class DropboxStream
{
	private $Client = false;

	private $temp_file_resource    = false;
	private $temp_file             = false;
	private $temp_folder           = false;
	
	private $file_open_for_reading = false;
	
	private $file_path             = false;
	private $file_mode             = false;
	
	private $current_folder        = false;
	private $current_folder_pos    = 0;

	public function __construct()
	{
		$dropbox_config    = PerchConfig::get('dropbox');
		$Perch             = Perch::fetch();

		$app 			   = new DropboxApp(null, null, $dropbox_config['access_token']);
		$this->Client      = new Dropbox($app);
				
		$conf              = PerchConfig::get('env');
		$this->temp_folder = $conf['temp_folder'];
	}

	public function dir_closedir() 
	{
		$this->current_folder     = false;
		$this->current_folder_pos = 0;
		return true;
	}

	public function dir_opendir($path, $options) 
	{
		$path   = $this->clean_path($path);
		
		$listFolderContents = $this->Client->listFolder($path);

		if (is_object($listFolderContents)) {
			$this->current_folder = $listFolderContents->getItems();
			return true;
		}
		
		return false;
	}

	public function dir_readdir() 
	{
		if (isset($this->current_folder[$this->current_folder_pos])) {
			$file_path     = $this->current_folder[$this->current_folder_pos]->getPathLower();
			$file_segments = explode('/', $file_path);
			$this->current_folder_pos++;
			return array_pop($file_segments);
		}

		return false;
	}

	public function dir_rewinddir() 
	{
		$this->current_folder_pos = 0;
	}

	public function mkdir($path, $mode=null, $options=null) 
	{
		$path   = $this->clean_path($path);
		$result = $this->Client->createFolder($path);

		if (is_array($result)) return true;
		return false;
	}

	public function rename($path_from=null, $path_to=null) 
	{
		$path_from = $this->clean_path($path_from);
		$path_to   = $this->clean_path($path_to);
		$result    = $this->Client->move($path_from, $path_to);

		if (is_array($result)) return true;
		return false;
	}

	public function rmdir($path=null, $options=null) 
	{
		$path   = $this->clean_path($path);
		$result = $this->Client->delete($path);

		if (is_array($result)) return true;
		return false;
	}

	public function stream_cast($cast_as=null) 
	{
		$this->log(__METHOD__, func_get_args());
	}

	public function stream_close() 
	{
		$this->file_path             = false;
		$this->file_mode             = false;
		$this->file_open_for_reading = false;

		if (is_resource($this->temp_file_resource)) {
			fclose($this->temp_file_resource);	
		}
		unlink($this->temp_file);
		
		return true;
	}

	public function stream_eof() 
	{
		return feof($this->temp_file_resource);
	}

	public function stream_flush() 
	{
		fseek($this->temp_file_resource, 0);

		$this->Client->simpleUpload($this->temp_file, $this->file_path);

		return true;
	}

	public function stream_lock($operation=null) 
	{
		$this->log(__METHOD__, func_get_args());
	}

	public function stream_metadata($path=null, $option=null, $value=null) 
	{
		$this->log(__METHOD__, func_get_args());
	}

	public function stream_open($path=null, $mode=null, $options=null, $opened_path=null) 
	{
		$this->file_path          = $this->clean_path($path);
		$this->file_mode          = $mode;
		$this->temp_file_resource = $this->get_temp_file();
		return true;
	}

	public function stream_read($count=null) 
	{
		if (!$this->file_open_for_reading) {
			
			$file = $this->Client->download($this->file_path);
			if ($file) {
				fwrite($this->temp_file_resource, $file->getContents());
			}
			
			if (!is_resource($this->temp_file_resource)) {
				$this->temp_file_resource = fopen($this->temp_file, 'r');
			}else{
				fseek($this->temp_file_resource, 0);
			}
			
			$this->file_open_for_reading = true;
		}

		return fread($this->temp_file_resource, $count);		
	}

	public function stream_seek($offset=null, $whence=null) 
	{
		if (is_resource($this->temp_file_resource)) {
			fseek($this->temp_file_resource, $offset, $whence);
		}
	}

	public function stream_set_option($option=null, $arg1=null, $arg2=null) 
	{
		$this->log(__METHOD__, func_get_args());
	}

	public function stream_stat() 
	{
		$this->log(__METHOD__, func_get_args());
	}

	public function stream_tell() 
	{
		$this->log(__METHOD__, func_get_args());
	}

	public function stream_truncate($new_size=null) 
	{
		$this->log(__METHOD__, func_get_args());
	}

	public function stream_write($data=null) 
	{
		fwrite($this->temp_file_resource, $data);
		return strlen($data);
	}

	public function unlink($path=null) 
	{
		$path   = $this->clean_path($path);
		$result = $this->Client->delete($path);

		if (is_array($result)) return true;
		return false;
	}

	public function url_stat($path, $flags) 
	{
		$path = $this->clean_path($path);
		
		try {
			$metadata = $this->Client->getMetadata($path);

			if ($metadata) {

				$d    = strtotime($metadata->getClientModified());
				$mode = 0;

				if ($metadata->getDataProperty('.tag')=='folder') {
					$mode = 040777;
				}

				$out = [
					'dev'     => 0,
					'ino'     => 0,
					'mode'    => $mode,
					'nlink'   => 0,
					'uid'     => 0,
					'gid'     => 0,
					'rdev'    => 0,
					'size'    => (int) $metadata->getSize(),
					'atime'   => $d,
					'mtime'   => $d,
					'ctime'   => $d,
					'blksize' => 0,
					'blocks'  => 0,
				];

				foreach($out as $val) $out[] = $val;
				return $out;
			}
		} catch (Exception $e) {
			return false;	
		}

		return false;
	}

	private function clean_path($path)
	{
		return str_replace('dropbox://', '/', $path);
	}

	private function log($method, $args)
	{
		//$s = "Calling method '$method' ". implode(', ', $args). "\n";
		//file_put_contents(__DIR__.'/log.txt', $s, FILE_APPEND);
		//error_log("Dropbox stream wrapper does not yet implement method: $method");
	}

	private function get_temp_file()
	{
		$f = tempnam($this->temp_folder, 'dbx_');
		$this->temp_file = $f;
		return fopen($f, 'w+');
	}

}



