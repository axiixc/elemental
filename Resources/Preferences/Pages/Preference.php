<?php # Pages Preference [axiixc]

/* Still have to work out the duplicate slug problem */

# Variable Work
$_GET['action'] = crunch($_GET['action']);
$_GET['section'] = crunch($_GET['section']);
$actions = array('new' => 'new_page', 'edit' => 'write_page', 'delete' => 'delete_page');

# Setup sidebar
$a_sidebar[] = array('name' => 'New Page', 'link' => parse_link('ex://Preferences/Pages?new'));
sidebar('menu', 'title', 'Pages', 'content', menu($a_sidebar, true));
unset($a_sidebar);

# Action Functions
function new_page() {
	MySQL::query("INSERT INTO `[database]`.`[prefix]pages` (`id`, `slug`, `name`, `content`, `author`, `created`, `modified`) VALUES (NULL, '%1\$s', '%2\$s', '%3\$s', '%4\$s', '%5\$s', '%5\$s');", $_POST['slug'], $_POST['name'], $_POST['content'], Registry::fetch('UAuth')->id, date(SDFDay));
	$return = MySQL::query("SELECT `id`, `name` FROM `[prefix]pages` WHERE `slug` LIKE CONVERT(_utf8 '%1\$s' USING latin1) COLLATE latin1_swedish_ci AND `modified` LIKE CONVERT(_utf8 '%2\$s' USING latin1) COLLATE latin1_swedish_ci;", $_POST['slug'], date(SDFDay));
	$is = mysql_fetch_assoc($return);
	notification(UINotice, 'Page Added.<a href="'.parse_link("ex://Pages/{$is['id']}").'">'.$is['name'].'</a>');
}

function write_page() {
	MySQL::query("UPDATE `[database]`.`[prefix]pages` SET `slug` = '%1\$s', `name` = '%2\$s', `content` = '%3\$s', `modified` = '%4\$s' WHERE `[prefix]pages`.`id` = %5\$s LIMIT 1;", $_POST['slug'], $_POST['name'], $_POST['content'], date(SDFDay), $_POST['id']);
	notification(UINotice, 'Page Updated.<a href="'.parse_link("ex://Pages/{$_POST['id']}/").'">'.$_POST['name'].'</a>');
}

function delete_page() {
	$page = Registry::fetch('Handler')->read(mysql_safe($_GET['id']));
	MySQL::query("DELETE FROM `[prefix]pages` WHERE `[prefix]pages`.`id` = %s LIMIT 1", $_GET['id']);
	notification(UINotice, "{$page['name']} deleted.");
}

# Check and/or Call Functions
if(!is_null($actions[$_GET['action']])) call_user_func($actions[$_GET['action']]);

include application('Users');

if(isset($_GET['edit'])) {
	
	include package('TinyMCE');
	$tinyMCE->commit();
	
	if(!isset($_GET['id']) or is_null($_GET['id']) or $_GET['id'] == null) {
		error("No Page ID Given", "No page id supplied to edit. Do you want to <a href=\"".parse_link('ex://Preferences/Pages?new')."\">create one</a>?");
	} $page = Registry::fetch('Handler')->read(mysql_safe($_GET['id']));
	if($page === false) {
		error("Bad Page ID", "There is no page with an id of {$_GET['id']}. Do you want to <a href=\"".parse_link('ex://Preferences/Pages?new')."\">create one</a>?");
	} else {
		$page['content'] = html_safe($page['content']);
		add(<<<EOD
			<form name="edit_page" method="POST" action="?action=edit">
				<input type="hidden" name="id" value="{$_GET['id']}" />
				<input type="text" name="name" style="width:100%" value="{$page['name']}" /><br />
				<input type="text" name="slug" style="width:100%" value="{$page['slug']}" /><br />
				<textarea name="content" style="width:100%;height:400px;font-family:monospace;font-size:10px;">{$page['content']}</textarea><br />
				<input type="submit" value="Update" />
				<input disabled type="button" value="Save as Draft" />
			</form>
EOD
);
	}
	
} elseif(isset($_GET['new'])) {
	
	include package('TinyMCE');
	$tinyMCE->commit();
	
	add(<<<EOD
		<script>
			function auto_clear(me){if(me.value==me.defaultValue){me.value=''}}
			function make_slug(){if(document.new_page.slug.value==document.new_page.slug.defaultValue){document.new_page.slug.value=document.new_page.name.value.split(" ").join("-").toLowerCase();}}
		</script>
		<form name="new_page" method="POST" action="?action=new">
			<input type="text" name="name" style="width:100%" value="Page Name" onfocus="auto_clear(this)" onblur="make_slug()" /><br />
			<input type="text" name="slug" style="width:100%" value="Page Slug" onfocus="auto_clear(this)" /><br />
			<textarea name="content" style="width:100%;height:400px;font-family:monospace;font-size:10px"></textarea><br />
			<input type="submit" value="Publish" />
			<input disabled type="button" value="Save as Draft" />
		</form>
EOD
);
	
} else {
	
	$pages = Registry::fetch('Handler')->dump(
		(is_null($_GET['count'])) ? 30 : $_GET['count'],
		(is_null($_GET['page'])) ? 0 : $_GET['page'] * $_GET['count']
	);

	$template = (!is_null(Registry::fetch('Interface')->template('Preference Page List Item'))) ?
		Registry::fetch('Interface')->template('Preference Page List Item') :
		'<table style="width:100%%"><tr><td><h1><a href="%2$s">%3$s</a></h1></td><td><span style="float:right;text-align:right"><a href="%10$s">Edit</a><br />%11$s</span></td></tr><tr><td colspan="2">%4$10.300s <small>[...]</small></td></tr><tr><td colspan="2">Posted by <a href="%8$s">%9$s</a> on %6$s : Last modified on %5$s : ID %1$s</td></tr></table>';

	foreach($pages as $id => $page) {
		$author = new User($page['author']);
		add(sprintf(
			$template, 
			$page['id'], #1
			parse_link("ex://Pages/{$page['id']}/"), #2
			$page['name'], #3
			strip_tags($page['content']), #4
			format_date($page['modified'], $date_format), #5
			format_date($page['created'], $date_format), #6
			$author->id, #7
			$author->profile_link, #8
			$author->display_name, #9
			parse_link("ex://Preferences/Pages?edit&id={$page['id']}"), #10
			'<a href="javascript:if(confirm(\'Delete '.$page['name'].'? This cannot be undone.\')){document.location=\''.parse_link("ex://Preferences/Pages?action=delete&id={$page['id']}").'\';}">Delete</a>' #11
		));
	}
	
}