<?php # Filesystem Library [ axiixc ] : Basic Actions

# Directory Specific Functions
function dir_read($path, $full=true, $keyformat=null, $post='/') {
	if(file_exists($path)) {
		$temp = scandir($path);
		foreach($temp as $file) {
			if(!in_array(strtolower($file), array('.', '..', '.ds_store'))) {
				# Can be condensed?
				if($keyformat == crunch) $key = crunch(filename($file), true);
				if($keyformat == lc_filename) $key = strtolower(filename($file));
				elseif($keyformat == lc_basename) $key = strtolower($file);
				elseif($keyformat == uc_filename) $key = strtolower(filename($file));
				elseif($keyformat == uc_basename) $key = strtoupper($file);
				elseif($keyformat == filename) $key = filename($file);
				elseif($keyformat == basename) $key = $file;
				elseif($keyformat == position) $key = count($output) - 1;
				elseif($keyformat == path) $key = $path;
				elseif($keyformat == full_path) $key = $path . $file;
				if(!$full) $output[$key] = $file . $post;
				else $output[$key] = $path . '/' . $file . $post;
			}
		} return $output;
	}
}

function dir_make($path) { 
	if(!file_exists($path)) { 
		mkdir($path); 
		return true; 
	} else return false; 
}

# General Filesystem Functions
function file_rename($path,$rename) {
	$name = dirname($path) . '/' . $rename;
	if(!copy($path,$name)) return FALSE;
	else {
		$original_path = $path;
		$handler = opendir($path);
		while (true) {
			$item = readdir($handler);
			if ($item == "." or $item == "..") continue;
			elseif (gettype($item) == "boolean") {
				closedir($handler);
				if (!@rmdir($path)) return false;
				if ($path == $original_path) break;
				$path = substr($path, 0, strrpos($path, "/"));
				$handler = opendir($path);
			} elseif (is_dir($path."/".$item)) {
				closedir($handler);
				$path = $path."/".$item;
				$handler = opendir($path);
			} else unlink($path."/".$item);
		} return true;
	}
}

function file_move($path, $to) { return FSRename($path, $to); }
function file_copy($path,$to) { return copy($path, $to); }

function file_delete($path) {
	$original_path = $path;
	$handler = opendir($path);
	while (true) {
		$item = readdir($handler);
		if ($item == "." or $item == "..") continue;
		elseif (gettype($item) == "boolean") {
			closedir($handler);
			if (!@rmdir($path)) return false;
			if ($path == $original_path) break;
			$path = substr($path, 0, strrpos($path, "/"));
			$handler = opendir($path);
		} elseif (is_dir($path."/".$item)) {
			closedir($handler);
			$path = $path."/".$item;
			$handler = opendir($path);
		} else unlink($path."/".$item);
	} return true;
}

# FSPermissions, FSOwner, FSGroup Unsupported
function file_permissions($path, $permissions, $recursive) { return false; }
function file_owner($path, $owner, $recursive) { return false; }
function file_group($path, $group, $recursive) { return false; }

# File Specific Functions
function file_read($path, $username=false, $password=false) {
	if($username and $password) { # Remote (Auth)
		$auth = "$username@$password:";
		if(substr(ltrim($path), 8, -8) == 'https://') {
			$prefix = 'https://'; 
			$suffix = substr($path, 8);
		} elseif(substr(ltrim($path), 7, -7) == 'http://') {
			$prefix = 'http://';
			$suffix = substr($path, 7);
		} else {
			$prefix = null; 
			$auth = null; 
			$suffix = $path;
		} $use_path = $prefix . $auth . $suffix;
	} else $use_path = $path; # Local or Remote (No Auth)
	return file_get_contents($use_path);
}

function file_edit($path, $contents, $mode) {
	$handle = fopen($path, $mode);
	if(fwrite($handle, $contents)) return true;
	else return false;
	fclose($handle);
}

# Shortcuts, use if you wish
function file_write($path, $contents) { return FSEdit($path, $contents, w); }
function file_append($path, $contents) { return FSEdit($path, $contents, a); }
function file_make($path) { return FSEdit($path, nil, w); }