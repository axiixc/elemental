<?php # UI Menus Library : Functions to create and modify menu blocks

# NEEDS MAJOR WORK old from v.1

function UIMenu($id,$array=false,$return=false) {
	# Determin the menu ID 
	$t = mysql_fetch_assoc(EXMySQLQuery("SELECT * FROM `[prefix]menus` WHERE `name` = CONVERT(_utf8 '{$id}' USING latin1) COLLATE latin1_swedish_ci");
	$menu = $t['id'];

	$result = EXMySQLQueryOld("SELECT * FROM `[prefix]navigation` WHERE `menu` = {$menu} ORDER BY `rank` ASC LIMIT 0, 100 ");
	
	if($array) while($nav = mysql_fetch_assoc($result)) { 
		$output[$nav['id']]['link'] = $nav['link']; 
		$output[$nav['id']]['name'] = $nva['name']; 
	} else {
		$output = '<ul>'; # the opening list tag
		while($nav = mysql_fetch_assoc($result)) $output = $output."\t<li><a href=\"{$nav['link']}\">{$nav['name']}</a></li>\n";
		$output = $output.'</ul>'; # closing list tag
	} if($return) echo $output; else return $output;
}

$system['UI']['submenu-width'] = FALSE;
function UISubmenuReset() { 
	global $system; unset($system['UI']['submenu']); 
	$system['UI']['submenu-width'] = false; 
}

function UISubmenuUnset() { 
	global $system; 
	foreach(func_get_args() as $position) unset($system['UI']['submenu'][$position]); 
}

function UISubmenuFixedWidth() { 
	global $system; $args = func_get_args(); $width = $args[0]; unset($args[0]); sort($args);
	foreach($args as $position) $system['UI']['submenu-width-' . $position] = $width; 
}

function UISubmenuAppend($contents,$position) {
	global $system;
	if (is_null($system['UI']['submenu'][$position]) or !isset($system['UI']['submenu'][$position])) $system['UI']['submenu'][$position] = $contents;
	else $system['UI']['submenu'][$position] = $system['UI']['submenu'][$position].$contents;
}

function UISubmenu() {
	global $system;
	$menu = $system['UI']['submenu'];
	if($system['UI']['submenu-width'] != false and $system['UI']['submenu-width'] > 0) $w = " style=\"width:{$system['UI']['submenu-width']}\"";
	
	# Figure what blocks we need and create them.
	$use = null;
	if($menu['left'] != null) $use = "\t<td class=\"left\"$w>{$menu['left']}</td>\n";
	if($menu['center'] != null) $use = $use."\t<td class=\"center\"$w>{$menu['center']}</td>\n";
	if($menu['right'] != null) $use = $use."\t<td class=\"right\"$w>{$menu['right']}</td>\n";
	
	# Put it all together (maybe)
	if ($use != null) echo "<table cellspacing=\"0\" class=\"UISubmenu\"><tr>$use</tr></table>";
	else echo "<table cellspacing=\"0\" class=\"UISubmenu\"><tr><td>&nbsp;</td></tr></table>";
}