<?php # UI Sidebar Library : Create and Modify sidebars

# Probably needs work
# Um.. how to tie into UI.ui

# New system uses an id assigned on creation
$system['UI']['sidebar-counter'] = 0;
function UISidebarWrite($title=null,$content=null,$col=null,$id=false) {
	global $system;
	if($id == false) {
		$id = $system['UI']['sidebar-counter'];
		$tmp[$id]['title'] = $title;
		$tmp[$id]['content'] = $content;
		if(in_array($col,$col_types)) $tmp[$id]['col'] = $col;
		else $tmp[$id]['col'] = 'sidebar-1';
		# Write the tmp array
		$system['UI']['sidebar'] = $tmp;
		$system['UI']['sidebar-counter'] = $id + 1;
		return $id;
	} else {
		if(!is_null($title)) $tmp['title'] = $title;
		if(!is_null($content)) $tmp['content'] = $content;
		if(!is_null($col) and in_array($col,$col_types)) $tmp['col'] = $col;
		# And write it
		$system['UI']['sidebar'][$id] = $tmp;
		return $id;
	}
}

# seperate multiple with comma, spaces removed automagically
# true will output ALL sidebar objects
# true can not be used with other col ids
function UISidebar($col) {
	global $system;
	if($col == true) $cols_active[0] = true;
	else $cols_active = explode(',', str_replace(' ', null, $col));
	$template = '<div class="item" id="%s"><h1>%s</h1>%s</div>';
	foreach($cols_active as $col) if(!$system['sidebar-count'] == 0) {
		foreach($system['UI']['sidebar'] as $id => $bar) if($col == $bar['col'] or $col == true) 
			$sb_object = $sb_object.sprintf($template,$bar['title'],$bar['content']);
		echo $sb_object; return true;
	} else return false;
}