<?php

function EXInString($pointer, $input, $case=false, $word=false) {
	if($word) $word = '\b'; else $word = null;
	if(!$case) $case = 'i'; else $case = null;
	$match = '/' . $word . $pointer . $word . '/' . $case; #echo "\n".$match.' ';
	if(preg_match($match, $input)) return true; else return false;
}

function EXHTMLSafe($str) {
	$str = str_replace('<','&lt;',$str);
	$str = str_replace('>','&gt;',$str);
	return str_replace('"','&quot;',$str);
}

function EXJSSafe($str) {
	$str = str_replace("'","\\'",$str);
	$str = str_replace("\n","\\n",$str);
	return str_replace("\t","\\t",$str);
}

function EXMySQLSafe($str, $char="\\'\"") {
	$chars = str_split($char);
	foreach($chars as $i) $str = str_replace($i, "\\$i", $str);
	return $str;
}

function filename($str) { $nfo = pathinfo($str); return $nfo['filename']; }

function EXWordsSubStr($str_String, $int_Length) {
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

function EXConvertDate() { /* Unsuported */ }

function EXContentType($input) {
	$mime_types = EXFetchResources('mime_types');
	list($dir, $base, $ext, $file) = pathinfo($input);
	return isset($mime_types[$ext]) ? $mime_types[$ext] : 'application/octet-stream';
}