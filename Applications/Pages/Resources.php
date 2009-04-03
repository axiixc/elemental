<?php # Pages Resources [axiixc]

class Pages {
	
	public $foo = 'yes';
	
	public function __construct() {}
	
	public function read($x) {
		if(is_string($x)) return $this->read_with_slug($x);
		elseif(is_array($x)) {
			foreach($x as $y) $r[] = $this->read($y);
			return $r;
		} else return $this->read_with_id($x);
	}
	
	public function read_with_slug($slug) {
		$result = MySQL::query();
		if(mysql_num_rows($result) > 0) return mysql_fetch_assoc($result);
	}
	
	public function read_with_id($id) {
		$result = MySQL::query();
		if(mysql_num_rows($result) > 0) return mysql_fetch_assoc($result);
	}
	
	public function write() {
		$args = func_get_args();
		$sid = array_shift($args);
		$args = eoargs($args);
		add(print_r($args, true));
	}
	
}