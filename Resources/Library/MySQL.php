<?php # MySQL : Init the database connection and handle queries

class MySQL {
	
	private $base, $prefix;
	
	public function __construct() {
		include rsc . 'mysql_conf.php';
		$base = $mysql_base;
		$prefix = $mysql_prefix;
		if(!mysql_connect($mysql_host,$mysql_user,$mysql_pass)) echo mysql_error();
		if(!mysql_select_db($mysql_base)) echo mysql_error();
	}
	
	public function query() {
		$args = func_get_args();
		$sql = array_shift($args);
		$sql = str_replace('[prefix]', $this->prefix, $sql);
		$sql = str_replace('[database]', $this->base, $sql);
		if(count($args) > 0) $sql = vsprintf($sql, $args);
		return mysql_query($sql);
	}
	
	public function writeConf($user,$pass,$host,$base,$prefix) {
		$template = "<?php\n\t\$date = '%s';\n\t\$mysql_user = '%s';\n\t\$mysql_pass = '%s';\n\t\$mysql_host = '%s';\n\t\$mysql_base = '%s';\n\t\$mysql_prefix = '%s';";
		$output = sprintf($template, date(EXDateSortableDateFormat), $user, $pass, $host, $base, $prefix);
		$handle = fopen(rsc.'MySQLConf.php',w);
		$r = fwrite($handle,$output);
		fclose($handle);
		return $r;
	}
	
}