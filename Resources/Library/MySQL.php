<?php # MySQL : Init the database connection and handle queries

include 'Dictionary.php';

class MySQL {
	
	private static $base, $prefix, $conf_file;
	
	private function __construct() {
	}
	
	public static function connect() {
		self::$conf_file = root.'Resources/mysql_conf.php';
		include self::$conf_file;
		self::$base = $mysql_base;
		self::$prefix = $mysql_prefix;
		if(!mysql_connect($mysql_host,$mysql_user,$mysql_pass)) echo mysql_error();
		if(!mysql_select_db($mysql_base)) echo mysql_error();
	}
	
	public static function query() {
		$args = func_get_args();
		$sql = array_shift($args);
		$sql = str_replace('[prefix]', self::$prefix, $sql);
		$sql = str_replace('[database]', self::$base, $sql);
		if(count($args) > 0) $sql = vsprintf($sql, $args);
		return mysql_query($sql);
	}
	
	public static function writeConf($user,$pass,$host,$base,$prefix) {
		$template = "<?php\n\$type = '%s';\n\$date = '%s';\n\$mysql_user = '%s';\n\$mysql_pass = '%s';\n\$mysql_host = '%s';\n\$mysql_base = '%s';\n\$mysql_prefix = '%s';\n?>";
		$output = sprintf($template, 'automated', date(EXDateSDF_Day), $user, $pass, $host, $base, $prefix);
		$handle = fopen(self::$conf_file, w);
		$r = fwrite($handle,$output);
		fclose($handle);
		return $r;
	}
	
}

MySQL::connect();