<?php

class System {
	
	public $app;
	public $override = false;
	
	public function __construct() {
		# Application
		if(isset($_GET['app'])) $this->app = strtolower(path_safe($_GET['app']));
		else $this->app = strtolower(Conf::read('Application'));
	}
	
	public function diagnostics($return=false) {
		$output['Application'] = $this->app;
		$output['override'] = $this->override;
		return diagnostic($output, $return);
	}
	
	public function end($reason) {
		$die = import("Permanant Session Log");
		$die[time()] = $reason;
		register_resource("Permanant Session Log", $die);
		die("Your session was forcefully killed by the watchdog.");
	}
	
}

function package($id) {
	if(file_exists(root."Resources/Packages/$id/Package.php")) {
		return root."Resources/Packages/$id/Package.php";
	} else if(file_exists(root."Packages/$id")) {
		Log::write("package($id) Not a valid package.");
		return nil;
	} else {
		Log::write("package($id) Package does not exist.");
		return nil;
	}
}

function library($id) {
	if(file_exists(root."Resources/Library/$id.php")) {
		return root."Resources/Library/$id.php";
	} else {
		Log::write("library($id) Library does not exist.");
		return nil;
	}
}