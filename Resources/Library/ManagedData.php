<?php

class ManagedData {
	
	public $data = array(); # History of all read actions
	protected $check = null; # Check for available MD Session
	protected $structure = array(); # Contains info for read/write
	protected $entity = null; # Name of the MD Session
	protected $time; # Not sure yet, the time the MD Session was recalled
	
	public function __construct($entity) {
		$this->time = microtime();
		$this->entity = $entity;
		$temp = MySQL::query();
		
		if(!mysql_num_rows($temp) == 1) { # ManagedData Table Does Not Exist
			Log::write("ManagedData::__construct($entity) Entity does not exist, use MDInstaller to create.");
			$this->check = false;
		} else {
			$t = mysql_fetch_assoc($temp);
			$this->structure['name'] = $t['structure'];
			$this->structure['layout'] = unfold($t['layout']);
			$this->check = true;
		}
	}
	
	public function info($i_type='both', $echo=true) {
		if($this->check === false) {
			Log::write("ManagedData::info($i_type, $echo) Session not initiated. [$this->entity]");
		} else {
			if($echo) {
				switch($i_type) {
					case 'name' | 'both':
						echo $this->structure['name']."\n";
						break;
					
					case 'layout' | 'both':
						print_r($this->structure['layout']);
						break;
						
					default:
						Log::write("ManagedData::info($i_type, $echo Invalid info type. [$this->entity]");
						break;
				}
			} else {
				switch($i_type) {
					case 'name':
						return $this->structure['name'];
						break;
						
					case 'layout':
						return $this->structure['layout'];
						break;
						
					default:
						return $this->structure;
						break;
				}
			}
		}
	}
	
	public function read($condition, $limit) {
		
	}
	
	public function write() {
		$args = func_get_args();
	}
	
	public function delete($condition, $limit) {
		
	}
	
}

class MDInstaller extends ManagedData {
	
	public function __construct($entity) {
		parent::__construct($entity);
		if($check === true) {
			Log::write("MDInstaller::__construct($entity) Table already exists. [$this->entity]");
		}
	}
	
	public function newFromTemplate($template) {
		
	}
	
	public function newFromArray($template) {
		
	}
	
	public function useInstaller($path_to_resource) {
		
	}
	
	public function populate($array) {
		
	}
	
}

class MDPackager extends ManagedData {
	
	public function __construct($entity) {
		parent::__construct($entity);
	}
	
	public function data() {
		
	}
	
	public function structure() {
		
	}
	
	public function dump() {
		
	}
	
	public function createInstaller($path_to_export) {
		
	}
	
}