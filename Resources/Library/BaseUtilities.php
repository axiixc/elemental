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
	return str_replace(' ', '-', strtolower($string));
}

/* this-string-to -> This String To */
function uncrunch($string) {
	return ucwords(str_replace('-', ' ', $string));
}

function is_even($x) {
	if($x&1) return false;
	else return true;
}

function is_odd($x) {
	if($x&1) return true;
	else return false;
}

function diagnostic($output, $return=false) {
	$colors = Conf::read("Diagnostic Styles");
	foreach($output as $name => $value) {
		if(is_null($value)) $value = 'NULL';
		if($value === true) $value = 'TRUE';
		if($value === false) $value = 'FALSE';
		$value = str_replace('NULL', '<span style="color:yellow">NULL</span>', $value);
		$value = str_replace('TRUE', '<span style="color:green">TRUE</span>', $value);
		$value = str_replace('FALSE', '<span style="color:red">FALSE</span>', $value);
		$x .= sprintf('<span style="color:#2C68C1;">[%s]</span>&nbsp;%s<br />', uncrunch($name), $value);
	}
	if($return) return $x; else { echo $x; return null; }
}