<?php # Utilites [axiixc]

function path_safe($path, $strict=false) {
	if($strict) {
		$path = preg_replace('/[.]{2,}/',null,$path);
		$path = str_replace('/',null,$path);
		$path = str_replace('\\',null,$path);
	} else {
		$path = str_replace('/',null,$path);
		$path = str_replace('\\',null,$path);
	} return $path;
}

function mysql_safe($str, $char="\\'\"") {
	$chars = str_split($char);
	foreach($chars as $i) $str = str_replace($i, "\\$i", $str);
	return $str;
}

function html_safe($str) {
	$str = str_replace('<', '&lt;', $str);
	return str_replace('>', '&gt;', $str);
}

function filename($str) { $nfo = pathinfo($str); return $nfo['filename']; }

function crunch($string) {
	return str_replace(' ', '-', str_replace('_', '-', strtolower($string)));
}

function uncrunch($string, $expand_to=' ') {
	return ucwords(str_replace('-', $expand_to, $string));
}

function is_even($x) {
	if($x&1) return false;
	else return true;
}

function is_odd($x) {
	if($x&1) return true;
	else return false;
}

function eoargs($array) {
	if(is_even(count($array))) {
		$i = 0;
		do {
			$a = $i; $b = $i + 1;
			$x[crunch($array[$a])] = $array[$b];
			$i = $i + 2;
		} while($i <= count($array));
		return $x;
	} else {
		Log::write("eoargs() Bad argument layout. Make sure argument count, including key, is an odd number.");
	}
}

function diagnostic($output, $return=false) {
	foreach($output as $name => $value) {
		if(is_null($value)) $value = 'NULL';
		if($value === true) $value = 'TRUE';
		if($value === false) $value = 'FALSE';
		$value = str_replace('NULL', Registry::fetch('Interface')->template('Diagnostic NULL'), $value);
		$value = str_replace('TRUE', Registry::fetch('Interface')->template('Diagnostic TRUE'), $value);
		$value = str_replace('FALSE', Registry::fetch('Interface')->template('Diagnostic FALSE'), $value);
		$x .= sprintf(Registry::fetch('Interface')->template('Diagnostic Item'), uncrunch($name), $value);
	}
	if($return) return sprintf(Registry::fetch('Interface')->template('Diagnostic'), $x); 
	else { printf(Registry::fetch('Interface')->template('Diagnostic'),$x); return null; }
}

function in_string($pointer, $input, $case=false, $word=false) {
	if($word) $word = '\b'; else $word = null;
	if(!$case) $case = 'i'; else $case = null;
	$match = '/' . $word . $pointer . $word . '/' . $case; #echo "\n".$match.' ';
	if(preg_match($match, $input)) return true; else return false;
}

function wcsubstr($str_String, $int_Length) {
	# (c) Ingo Renner (infoATingo-rennerDOTcom) www.ingo-renner.com 2003/01/18  
	$str_String   = trim($str_String);
	$str_String   = substr($str_String, 0, $int_Length);
	$str_Revstr   = strrev($str_String);
	$str_LastChar = substr($str_Revstr, 0, 1);
	if($str_LastChar == " ") return substr($str_String, 0, -1);
	else {
		$arr_Words = explode(" ", $str_String);
		$int_Elements = count($arr_Words);
		if($int_Elements == 1) return $arr_Words[0];
		else {
			array_pop($arr_Words);
			$str_String = implode(" ", $arr_Words);
			return $str_String;
		}
	}
} 

function format_date($is=null, $want=null) {
	$want = (is_null($want)) ? Conf::read("Date Format") : $want ;
	return (is_null($is)) ? date($want) : date($want, strtotime($is)) ;
}

function content_type($input) {
	$mime_types = EXFetchResources('mime_types');
	list($dir, $base, $ext, $file) = pathinfo($input);
	return isset($mime_types[$ext]) ? $mime_types[$ext] : 'application/octet-stream';
}