<?php

function EXConfRead($key) {
	$data = EXMySQLQueryOld("SELECT * FROM `[prefix]conf` WHERE `key` = CONVERT(_utf8 '$key' USING latin1) COLLATE latin1_swedish_ci");
	if(mysql_error()) return false;
	else {
		$output = mysql_fetch_assoc($data);
		return $output['value'];
	}
}

function EXConfWrite($key,$value) {
	if(EXConfRead($key)) EXMySQLQueryOld("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`) VALUES ('$key', '$value');");
	else EXMySQLQueryOld("UPDATE `[database]`.`[prefix]conf` SET `value` = '$value' WHERE CONVERT( `[prefix]conf`.`key` USING utf8 ) = '$key' LIMIT 1 ;");
	if(mysql_error()) return false;
	else return true;
}

function EXConfDelete($key) {
	EXMySQLQueryOld("DELETE FROM `[prefix]conf` WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '{$key}' LIMIT 1");
	if(mysql_error()) return false;
	else return true;
}

/* Table MUST BE in ConfTable Format
 * key - primary key, varchar, 500
 * value - text
 * Other rows my be added also, however they will not affect the result
 */
function EXConfCustomRead($key,$table) {
	$data = EXMySQLQueryOld("SELECT * FROM `[prefix]{$table}` WHERE `key` = CONVERT(_utf8 '$key' USING latin1) COLLATE latin1_swedish_ci");
	if(mysql_error()) return false;
	else {
		$output = mysql_fetch_assoc($data);
		return $output['value'];
	}
}

function EXConfCustomWrite($key,$value,$table) {
	if(EXConfReadFromCustomTable($key,$table)) EXMySQLQueryOld("INSERT INTO `[database]`.`[prefix]{$table}` (`key`, `value`) VALUES ('$key', '$value');");
	else EXMySQLQueryOld("UPDATE `[database]`.`[prefix]{$table}` SET `value` = '$value' WHERE CONVERT( `[prefix]{$table}`.`key` USING utf8 ) = '$value' LIMIT 1 ;");
	if(mysql_error()) return false;
	else return true;
}

function EXConfCustomDelete($key,$table) {
	EXMySQLQueryOld("SELECT * FROM `[prefix]{$table}` WHERE `key` = CONVERT(_utf8 '$key' USING latin1) COLLATE latin1_swedish_ci");
	if(mysql_error()) return false;
	else return true;
}