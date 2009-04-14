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
	$id = crunch($id);
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
	$id = crunch($id);
	if(file_exists(root."Resources/Library/$id.php")) {
		return root."Resources/Library/$id.php";
	} else {
		Log::write("library($id) Library does not exist.");
		return nil;
	}
}

function application($id) {
	$id = crunch($id);
	if(file_exists(root."Applications/$id/Resources.php")) {
		return root."Applications/$id/Resources.php";
	} else {
		if(file_exists(root."Applications/$id")) Log::write("application($id) Application does not exist.");
		elseif(file_exists(root."Applications/$id/Resources.php")) Log::write("application($id) Application does not contain a resource file.");
		return nil;
	}
}