<?php

function EXInString($pointer, $input, $case=false, $word=false) {
	if($word) $word = '\b'; else $word = null;
	if(!$case) $case = 'i'; else $case = null;
	$match = '/' . $word . $pointer . $word . '/' . $case; #echo "\n".$match.' ';
	if(preg_match($match, $input)) return true; else return false;
}

#if(EXInString('hello', 'hellohowareyou', false, false))   echo 'pass'; else echo 'fail';
#if(EXInString('hello', 'hellohowareyou', false, true))    echo 'fail'; else echo 'pass';
#if(EXInString('hello', 'hello how are you', false, true)) echo 'pass'; else echo 'fail';
#if(EXInString('hello', 'hEllohowareyou', true, false))    echo 'fail'; else echo 'pass';
#if(EXInString('hello', 'hEllo how are you', true, true))  echo 'fail'; else echo 'pass';
#if(EXInString('hEllo', 'hEllohowareyou', true, false))    echo 'pass'; else echo 'fail';
#if(EXInString('hEllo', 'hEllohowareyou', true, true))     echo 'fail'; else echo 'pass';
#if(EXInString('hEllo', 'hEllo how are you', true, true))  echo 'pass'; else echo 'fail';

function EXPathSafe($path, $strict=false) {
	if($strict) {
		$path = preg_replace('/[.]{2,}/',null,$url);
		$path = str_replace('/',null,$url);
		return  str_replace('\\',null,$url);
	} else {
		$path = str_replace('/',null,$url);
		return  str_replace('\\',null,$url);
	}
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

# Fix or forget
function EXConvertDate($date=null,$format=null) {
	$months = array('01' => 'January', '02' => 'Feburary', '03' => 'March', '04' => 'April', 
	'05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', 
	'10' => 'October', '11' => 'November', '12' => 'December');
	
	if($date == null || $format == null) return FALSE;
	else {
		
		$date = array(
			'yearLong'         => substr($date,0,4),
			'yearShort'        => substr($date,0,2),
			
			'monthNumWithZero' => substr($date,4,2),
			'monthNum'         => substr($date,5,1),
			'monthName'        => null,
			
			'dayNumWithZero'   => substr($date,6,2),
			'dayNum'           => substr($date,7,1),
			'dayName'          => null // Work in progress feature
		);
			
		$date['monthName'] = $months[$date['monthNumWithZero']];	
		
		$key = array(
			'yearLong'         => '%Y',
			'yearShort'        => '%y',

			'monthNumWithZero' => '%o',
			'monthNum'         => '%m',
			'monthName'        => '%M',
			
			'dayNumWithZero'   => '%a',
			'dayNum'           => '%d',
			'dayName'          => '%D'
		);
			
		return str_replace($key,$date,$format);
	}
}

function EXContentType($input) {
	include(EXLibrary('Filetypes.php'));
	list($dir, $base, $ext, $file) = pathinfo($input);
	return isset($mime_types[$ext]) ? $mime_types[$ext] : 'application/octet-stream';
}