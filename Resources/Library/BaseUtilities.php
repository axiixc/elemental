<?php # Base Utilities [ axiixc ] : Most used or system required

function path_safe($path, $strict=false) {
	if($strict) {
		$path = preg_replace('/[.]{2,}/',null,$url);
		$path = str_replace('/',null,$url);
		return  str_replace('\\',null,$url);
	} else {
		$path = str_replace('/',null,$url);
		return  str_replace('\\',null,$url);
	}
}

function mysql_safe($str, $char="\\'\"") {
	$chars = str_split($char);
	foreach($chars as $i) $str = str_replace($i, "\\$i", $str);
	return $str;
}

function filename($str) { $nfo = pathinfo($str); return $nfo['filename']; }

function fold($myarray,$EXFold_output=null,$EXFold_parentkey=null) {
	foreach($myarray as $key=>$value){
		if (is_array($value)) {
			$EXFold_parentkey .= $key.MLDF;
			EXFold($value,$EXFold_output,$EXFold_parentkey);
			$EXFold_parentkey = "";
		} else $EXFold_output .= $EXFold_parentkey.$key.MLDF.$value.MLDS;
	} return $EXFold_output;
}

function unfold($string) {
	$lines = explode(MLDS, $string);
	foreach ($lines as $value){
		$items = explode(MLDF, $value);
		if (sizeof($items) == 2) $myarray[$items[0]] = $items[1];
		else if (sizeof($items) == 3) $myarray[$items[0]][$items[1]] = $items[2];
	} return $myarray;
}

/* This String To -> this-string-to */
function crunch($string) {
	return str_replace(' ', '-', strtolower($string));
}

/* this-string-to -> This String To */
function uncrunch($string) {
	return ucwords(str_replace('-', ' ', $string));
}