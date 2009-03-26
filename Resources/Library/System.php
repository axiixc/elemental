<?php

class System {
	
	public $app, $ui, $directecho, $override;
	
	public function __construct() {
		# Application
		if(isset($_GET['app'])) $this->app = path_safe($_GET['app']);
		else $this->app = Conf::read('Application');
		
		# UI
		$this->ui = Conf::read('UI');
	}
	
	public function directecho($x=null) {
		if(is_null($x)) return $this->directecho;
		else $this->directecho = $x;
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

function library() {
	if(file_exists(root."Resources/Library/$id.php")) {
		return root."Resources/Library/$id.php";
	} else {
		Log::write("library($id) Library does not exist.");
		return nil;
	}
}