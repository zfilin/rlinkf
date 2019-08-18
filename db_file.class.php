<?php

	Class DBLinks implements IDBLinks
	{
		private $filename = '';
		private $db = array();
		private $is_connected = false;
		public $error_message = '';

		function __construct($options) {
			if(isset($options['file'])) $this->filename = $options['file'];
		}

		function __destruct()
		{
			if($this->is_connected) @file_put_contents($this->filename, serialize($this->db));
		}

		function Connect()
		{
			$this->error_message = '';
			if(empty($this->filename)) {
				$this->error_message = _('database filename can not be empty');
				return false;
			}
			if(!file_exists($this->filename))
				if(file_put_contents($this->filename, serialize($this->db))===false) {
					$this->error_message = sprintf(_('can`t create file %s'),$this->filename);
					return false;
				}
			if( !($str_db=file_get_contents($this->filename)) ) {
				$this->error_message = sprintf(_('can`t read file %s'),$this->filename);
				return false;
			}
			$this->db=unserialize($str_db);
			if (!is_array($this->db)) {
				$this->error_message = sprintf(_('file %s is not contained database array'),$this->filename);
				return false;
			}
			$this->is_connected = true;
			return true;
		}

		function Disconnect() {
			$this->error_message = '';
			if(!$this->is_connected) {
				$this->error_message = _('not connected to database');
				return false;
			}

			if(file_put_contents($this->filename, serialize($this->db))===false) {
				$this->error_message = sprintf(_('can`t write file %s'),$this->filename);
				return false;
			}

			$this->is_connected = false;
		}

		function GetLink($linkname)
		{
			$this->error_message = '';
			if(!$this->is_connected) {
				$this->error_message = _('not connected to database');
				return false;
			}

			if(!isset($this->db[$linkname])) {
				$this->error_message = sprintf(_('links named %s is not found in database'),$linkname);
				return false;
			}

			return $this->db[$linkname];
		}

		function PutLink($linkname, $link, $overwrite=false) {
			$this->error_message = '';
			if(!$this->is_connected) {
				$this->error_message = _('not connected to database');
				return false;
			}

			if(!$overwrite && isset($this->db[$linkname])) {
				$this->error_message = sprintf(_('links named %s already exists in database'),$linkname);
				return false;
			}

			$this->db[$linkname] = $link;

			return true;
		}

		function GetAllLinks()
		{
			$this->error_message = '';
			if(!$this->is_connected) {
				$this->error_message = _('not connected to database');
				return false;
			}

			return $this->db;
		}

		function DelLink($linkname)
		{
			$this->error_message = '';
			if(!$this->is_connected) {
				$this->error_message = _('not connected to database');
				return false;
			}

			if(!isset($this->db[$linkname])) {
				$this->error_message = sprintf(_('links named %s is not found in database'),$linkname);
				return false;
			}

			unset($this->db[$linkname]);

			return true;
		}

	}

?>