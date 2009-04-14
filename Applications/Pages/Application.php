<?php # Pages [axiixc]

Registry::fetch('Interface')->uinterface('1Bar');
include application('Users');

if($_GET['arg'] == 'list' or !isset($_GET['arg']) or is_null($_GET['arg']) or $_GET['arg'] == null) {
	
	$pages = Registry::fetch('Application')->dump(
		(is_null($_GET['count'])) ? 30 : $_GET['count'],
		(is_null($_GET['page'])) ? 0 : $_GET['page'] * $_GET['count']
	);
	
	$template = (!is_null(Registry::fetch('Interface')->template('Page List Item'))) ?
		Registry::fetch('Interface')->template('Page List Item') :
		'<div style="margin-bottom:10px;padding:10px 0;" class="Pages_List_Item"><a style="font-size:15px;" href="%2$s" class="Pages_List_Item_A">%3$s</a><div style="margin-top:10px;" class="Pages_List_Item_Content">%4$10.300s</div></div>' ;
	
	foreach($pages as $id => $page)
		$author = new User($page['author']);
		add(
			sprintf(
				$template, 
				$id, #1
				Registry::fetch('Interface')->parse_link("ex://Pages/$id"), #2
				$page['name'], #3
				strip_tags($page['content']), #4
				$author->id, #5
				$author->profile_link, #6
				$author_name #7
			)
		);
} else {
	$page = Registry::fetch('Application')->read($_GET['arg']);
	
	$template = (!is_null(Registry::fetch('Interface')->template('Page Item'))) ? 
		Registry::fetch('Interface')->template('Page Item') : 
		'<div><h1>%2$s <div style="font-size:small;">Posted by <a href="%8$s">%9$s</a> on %5$s</div></h1><div>%3$s</div></div>';

	$author = new User($page['author']);
	$date_format = Conf::read('Date Format');

	add(
		sprintf(
			$template, 
			$id, #1
			$page['name'], #2
			$page['content'], #3
			format_date($page['modified'], $date_format), #4
			format_date($page['created'], $date_format), #5
			$page['author'], #6
			$author->id, #7
			$author->profile_link, #8
			$author->display_name #9
		)
	);
}