<?php # Base Utilities [ axiixc ] : Most used or system required

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

/* For Compatibility */
function fold($myarray) {
	return serialize($myarray);
}

function unfold($string) {
	return unserialize($string);
}

/* This String To -> this-string-to */
function crunch($string) {
	return str_replace(' ', '-', str_replace('_', '-', strtolower($string)));
}

/* this-string-to -> This String To */
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
		$value = str_replace('TRUE', Registry::fetch('Interface')->template('Diagnostic NULL'), $value);
		$value = str_replace('FALSE', Registry::fetch('Interface')->template('Diagnostic NULL'), $value);
		$x .= sprintf(Registry::fetch('Interface')->template('Diagnostic Item'), uncrunch($name), $value);
	}
	if($return) return $x; else { echo $x; return null; }
}