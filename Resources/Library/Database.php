<?php # MySQL [axiixc]

class Database
{

	private $prefix, $database, $queries;
	
	public function __construct()
	{
		include root . 'Resources/Configuration/MySQL.php';
		if (!mysql_connect($host, $username, $password))
	   {
			die('MySQL Connection Failed');
		}
		if (!mysql_select_db($database))
		{
			die('MySQL Database Select Failed');
		}
		$this->prefix = $prefix;
		$this->database = $database;
	}

	public function query($sql)
	{
		$sql = str_replace('[prefix]', $this->prefix, $sql);
		$sql = str_replace('[database]', $this->database, $sql);
		$this->queries[] = $sql;
		return mysql_query($sql);
	}
	
	public function configure($host, $username, $password, $database, $prefix)
	{
		$time = date(sdfMinute);
		$content = "
			<?php # Auto-Generated at $time
			\$host = '$host';
			\$username = '$username';
			\$password = '$password';
			\$database = '$database';
			\$prefix = '$prefix';
			?>
		";
		fsWrite(root . 'Resources/mysql.php', $content);
	}
	
}

/* Plain Function Accessors (special case name convention) */

function query()
{
	$args = func_get_args();
	$sql = array_shift($args);
	return System::Database()->query(vsprintf($sql, $args));
}