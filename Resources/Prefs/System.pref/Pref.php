<?php

UIMetaSetHeadTitle('System');

$result = EXMySQLQuery('SELECT * FROM `[prefix]conf`');
while($vars = mysql_fetch_assoc($result)) $sys[$vars['key']] = $vars['value'];

include root.'Resources/MySQLConf.php';

$default_app = '';
$default_ui  = '';

$mode_debug = sprintf();
$mode_dev   = sprintf();

# Basic Setup
UIAdd('<form action="?app=Preferences&page=System&action=Write" method="POST">');
UIAdd('<table cellspacing="0" width="100%">');

# System Info
UIAdd('<tr><td colspan="2"><h2>System Information</h2></td></tr>');
UIAdd('<tr><td>Title</td><td><input type="text" name="system-title" value="'.$sys['system-title'].'" /></td></tr>');
UIAdd('<tr><td>Tagline</td><td><input type="text" name="system-tagline" value="'.$sys['system-tagline'].'" /></td></tr>');
UIAdd('<tr><td>Head Title (%t Sys Title, %a App Title)</td><td><input type="text" name="system-head-title" value="'.$sys['system-head-title'].'" /></td></tr>');
UIAdd('<tr><td>Keywords</td><td><input type="text" name="system-keywords" value="'.$sys['system-keywords'].'" /></td></tr>');
UIAdd('<tr><td>Description</td><td><input type="text" name="system-description" value="'.$sys['system-description'].'" /></td></tr>');
UIAdd('<tr><td>Footer</td><td><input type="text" name="system-footer" value="'.$sys['system-footer'].'" /></td></tr>');

# System Defaults
UIAdd('<tr><td colspan="2"><h2>System Defaults</h2></td></tr>');
UIAdd('<tr><td>Default Application</td><td>'.$default_app.'</td></tr>');
UIAdd('<tr><td>Default UI</td><td>'.$default_ui.'</td></tr>');
UIAdd($mode_debug,$mode_dev);

# MySQL
UIAdd('<tr><td colspan="2"><h2>MySQL Settings</h2></td></tr>');
UIAdd('<tr><td>Host</td><td><input type="text" name="mysql-host" value="'.$mysql_host.'" /></td></tr>');
UIAdd('<tr><td>Database</td><td><input type="text" name="mysql-base" value="'.$mysql_base.'" /></td></tr>');
UIAdd('<tr><td>Prefix</td><td><input type="text" name="mysql-prefix" value="'.$mysql_prefix.'" /></td></tr>');
UIAdd('<tr><td>Username</td><td><input type="text" name="mysql-user" value="'.$mysql_user.'" /></td></tr>');
UIAdd('<tr><td>Password</td><td><input type="text" name="mysql-pass" value="'.$mysql_pass.'" /></td></tr>');
UIAdd('</table>');

# Custom
UIAdd('<table cellspacing="0" width="100%">');
UIAdd('<tr><td width="200"><b>Name</b></td><td><b>Value</b></td><td width="5">&nbsp;<b>&times;</b></td></tr>');
UIAdd('<tr><td colspan="2" style="text-align:center;"><input type="submit" name="submit" value="Write Configuration" /></td></tr>');

foreach($sys as $key => $var) {
	$key_word = ucwords(str_replace('-',' ',$key));
	$var = str_replace('"','&quot;',$var);
	$reserved = array('system-title', 'system-tagline', 'system-head-title', 'system-keywords', 'system-description', 'system-footer', 'default-application', 'default-ui', 'mode-debug', 'mode-dev');
	if(!in_array($var,$reserved))
		if(strlen($var) > 150) UIAdd("<tr><td>$key_word</td><td><textarea name=\"$key\">$var</textarea></td><td>&nbsp;<a href=\"javascript:EXSystemVarDelete('$key');\">&times;</a></td></tr>");
		else UIAdd("<tr><td>$key_word</td><td><input type=\"text\" name=\"$key\" value=\"$var\" /></td><td>&nbsp;<a href=\"javascript:EXSystemVarDelete('$key');\">&times;</a></td></tr>");
}

UIAdd('</table>');
UIAdd('</form>');

# New Vars
UIAdd('<table cellspacing="0" width="100%">');
UIAdd('<form action="?app=Preferences&page=System&action=new" method="POST">');
UIAdd('<tr><td><b>New Variable Name</b></td><td><b>New Variable Value</b></td><td>&nbsp;</td></tr>');
UIAdd('<tr><td><input type="text" name="n_key" /></td><td><input type="text" name="n_value" /></td><td><input style="width:100%;" type="submit" value="Add" /></td></tr>');
UIAdd('</table></form><br />');