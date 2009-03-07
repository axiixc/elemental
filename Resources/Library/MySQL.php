<?php # MySQL Library : Environment and Functions

include(rsc.'MySQLConf.php');
if(!mysql_connect($mysql_host,$mysql_user,$mysql_pass)) echo mysql_error();
if(!mysql_select_db($mysql_base)) echo mysql_error();
$system['prefix'] = $mysql_prefix;
$system['database'] = $mysql_base;

function EXMySQLQuery() {
	global $system; 
	$args = func_get_args();
	$sql = array_shift($args);
	$sql = str_replace('[prefix]', $system['prefix'], $sql);
	$sql = str_replace('[database]', $system['database'], $sql);
	if(count($args) > 0) $sql = vsprintf($sql, $args);
	return mysql_query($sql);
}

function EXMySQLSetConf($user,$pass,$host,$base,$prefix) {
	$template = "<?php\n\t\$date = '%s';\n\t\$mysql_user = '%s';\n\t\$mysql_pass = '%s';\n\t\$mysql_host = '%s';\n\t\$mysql_base = '%s';\n\t\$mysql_prefix = '%s';";
	$output = sprintf($template, date(EXDateSortableDateFormat), $user, $pass, $host, $base, $prefix);
	$handle = fopen(rsc.'MySQLConf.php',w);
	if(!fwrite($handle,$output)) return false; else return true;
	fclose($handle); return $output;
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

$i = EXFold($system);
echo $i."\n\n";
print_r(EXUnfold($i));