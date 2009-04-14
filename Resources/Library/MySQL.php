<?php # MySQL : Init the database connection and handle queries

class MySQL {
	
	private static $base, $prefix, $conf_file, $queries=array();
	
	private function __construct() {
	}
	
	public static function connect() {
		if(file_exists(root.'Resources/mysql_conf.php')) {
			self::$conf_file = root.'Resources/mysql_conf.php';
			include self::$conf_file;
			self::$base = $mysql_base;
			self::$prefix = $mysql_prefix;
			if(!mysql_connect($mysql_host,$mysql_user,$mysql_pass)) echo mysql_error();
			if(!mysql_select_db($mysql_base)) echo mysql_error();
		} else {
			Log::write("MySQL::connect() Configuration could not be found. Run MySQL::write_conf().");
		}
	}
	
	public static function query() {
		$args = func_get_args();
		$sql = array_shift($args);
		$sql = str_replace('[prefix]', self::$prefix, $sql);
		$sql = str_replace('[database]', self::$base, $sql);
		if(count($args) > 0) $sql = vsprintf($sql, $args);
		self::$queries[] = $sql;
		return mysql_query($sql);
	}
	
	public static function write_conf($user,$pass,$host,$base,$prefix) {
		$template = "<?php\n\$type = '%s';\n\$date = '%s';\n\$mysql_user = '%s';\n\$mysql_pass = '%s';\n\$mysql_host = '%s';\n\$mysql_base = '%s';\n\$mysql_prefix = '%s';\n?>";
		$output = sprintf($template, 'automated', date(EXDateSDF_Day), $user, $pass, $host, $base, $prefix);
		$handle = fopen(self::$conf_file, w);
		$r = fwrite($handle,$output);
		fclose($handle);
		return $r;
	}
	
	public static function diagnostics($return=false) {
		include self::$conf_file;
		$output['host'] = $mysql_host;
		$output['user'] = $mysql_user;
		$output['password'] = '********'; # = $mysql_pass; # If you really mean it
		$output['database'] = $mysql_base;
		$output['prefix'] = $mysql_prefix;
		$output['queries'] = "\n";
		foreach(self::$queries as $query) $output['queries'] .= "$query\n";
		return diagnostic($output, $return);
	}
	
}

MySQL::connect();