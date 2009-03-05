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
	$sql = vsprintf($sql, $args);
	return mysql_query($sql);
}

function EXMySQLQueryOld($sql) {
	global $system;
	$sql = str_replace('[prefix]', $system['prefix'], $sql);
	$sql = str_replace('[database]', $system['database'], $sql);
	$output = mysql_query($sql);
	if(!mysql_error()) return $output;
	else { echo mysql_error(); return false; }
}

function EXMySQLSetConf($user,$pass,$host,$base,$prefix) {
	$template = "<?php\n\t\$date = '%s';\n\t\$mysql_user = '%s';\n\t\$mysql_pass = '%s';\n\t\$mysql_host = '%s';\n\t\$mysql_base = '%s';\n\t\$mysql_prefix = '%s';";
	$output = sprintf($template, date(EXDateSortableDateFormat), $user, $pass, $host, $base, $prefix);
	$handle = fopen(rsc.'MySQLConf.php',w);
	if(!fwrite($handle,$output)) return false; else return true;
	fclose($handle); return $output;
}