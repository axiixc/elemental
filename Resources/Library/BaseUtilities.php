<?php # Base Utilities [ axiixc ] : Most used or system required

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

function EXMySQLSafe($str, $char="\\'\"") {
	$chars = str_split($char);
	foreach($chars as $i) $str = str_replace($i, "\\$i", $str);
	return $str;
}

function EXFold($myarray) {
	global $EXFold_output, $EXFold_parentkey;
	foreach($myarray as $key=>$value){
		if (is_array($value)) {
			$EXFold_parentkey .= $key.MLDF;
			EXFold($value,$output,$parentkey);
			$EXFold_parentkey = "";
		} else $EXFold_output .= $EXFold_parentkey.$key.MLDF.$value.MLDS;
	} return $EXFold_output;
}

function EXUnfold($string){
	$lines = explode(MLDS,$string);
	foreach ($lines as $value){
		$items = explode(MLDF,$value);
		if (sizeof($items) == 2) $myarray[$items[0]] = $items[1];
		else if (sizeof($items) == 3) $myarray[$items[0]][$items[1]] = $items[2];
	} return $myarray;
}