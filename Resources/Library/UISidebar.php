<?php # UI Sidebar Library : Create and Modify sidebars

function UISidebarWrite($title=null,$content=null,$col=null,$id=null) {
	global $system;
	if(!is_null($id) and !is_null($system['UI']['sidebar'][$id])) { # Edit Mode
		if(!is_null($title)) $system['UI']['sidebar'][$id]['title'] = $title;
		if(!is_null($content)) $system['UI']['sidebar'][$id]['content'] = $content;
		if(!is_null($col)) $system['UI']['sidebar'][$id]['col'] = $col;
	} else { # New Mode
		if(is_null($id)) $id = $system['UI']['sidebar-count'];
		$system['UI']['sidebar-count']++;
		if(is_null($col)) $col = UISidebarMain;
		$tmp = array('title' => $title, 'content' => $content, 'col' => $col);
		$system['UI']['sidebar'][$id] = $tmp;
	} return $id;
}

function UISidebarDelete($id) {
	global $system;
	unset($system['UI']['sidebar'][$id]);
}

function UISidebar($id=true) {
	global $system;
	$template = '<div class="item" id="%s"">%s%s</div>';
	if($id == true) $sb_items = $system['UI']['sidebar']; # Using all
	else { # Using only a few
		$cols_active = explode(',', str_replace(' ', null, $id));
		foreach($system['UI']['sidebar'] as $id => $col) 
			if(in_array($col['col'], $cols_active)) $sb_items[$id] = $col;
	} if(!is_null($sb_items)) foreach($sb_items as $id => $col) {
		if(!is_null($col['title'])) $hTag = "<h1>{$col['title']}</h1>";
		else $hTag = null;
		$sb_object = $sb_object . sprintf($template, $id, $hTag, $col['content']);
	} echo $sb_object;
}