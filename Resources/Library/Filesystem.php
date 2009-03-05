<?php # Filesystem Library : Basic Actions

# Directory Specific Functions
function FSDirRead($path, $full=true, $keyformat=null, $post='/') {
	if(file_exists($path)) {
		$dir_handle = @opendir($path);
		while($file = readdir($dir_handle)) {
			if($file != '.' and $file != '..' and strtolower($file) != '.ds_store') {
				# Can be condensed?
				if($keyformat == lower_case_filename) $key = strtolower(filename($file));
				elseif($keyformat == lower_case_basename) $key = strtolower($file);
				elseif($keyformat == upper_case_filename) $key = strtolower(filename($file));
				elseif($keyformat == upper_case_basename) $key = strtoupper($file);
				elseif($keyformat == filename) $key = filename($file);
				elseif($keyformat == basename) $key = $file;
				elseif($keyformat == position_in_array) $key = count($output) - 1;
				elseif($keyformat == path) $key = $path;
				elseif($keyformat == full_path) $key = $path . $file;
				if(!$full) $output[$key] = $file . $post;
				else $output[$key] = $path . $file . $post;
			}
		} closedir($dir_handle); return $output;
	} else return $output;
} # Think about using scandir() instead

function FSDirMake($path) { if(!file_exists($path)) { mkdir($path); return true; } else return false; }

# General Filesystem Functions
function FSRename($path,$rename) {
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

function FSMove($path, $to) { if(FSRename($path, $to)) return true; else return false; }
function FSCopy($path,$to) { if(copy($path,$to)) return true; else return false; }

function FSDelete($path) {
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

function FSPermissions($path, $permissions, $recursive) { return false; }
function FSOwner($path, $owner, $recursive) { return false; }
function FSGroup($path, $group, $recursive) { return false; }

# File Specific Functions
function FSRead($path, $username=false, $password=false) {
	if($username and $password) { $auth = "$username@$password:";
		if(substr(ltrim($path), 8, -8) == 'https://') { 
			$prefix = 'https://'; $suffix = substr($path, 8); 
		} elseif(substr(ltrim($path), 7, -7) == 'http://') { 
			$prefix = 'http://'; $suffix = substr($path, 7); 
		} else { 
			$prefix = null; $auth = null; $suffix = $path;
		} $use_path = $prefix . $auth . $suffix;
	} return file_get_contents($use_path);
}

function FSEdit($path, $contents, $mode) {
	$handle = fopen($path, $mode);
	if(fwrite($handle, $contents)) return true;
	else return false;
	fclose($handle);
}

function FSWrite($path, $contents) { return FSEdit($path, $contents, w); }
function FSAppend($path, $contents) { return FSEdit($path, $contents, a); }
function FSMake($path) { return FSEdit($path, nil, w); }