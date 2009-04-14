<?php # Blog [axiixc] : Basic blog application

$id = (isset($_GET['arg'])) ? mysql_safe($_GET['arg']) : Conf::read("Pages Default");
$date_format = Conf::read("Date Format");
include application('Users');
include root."Applications/Blog/Date_Arrays.php";

if($id == 'list' or is_null($id) or $id == null) { # List View
	Log::write('Blog: LIST');
	
	$data = Registry::fetch('Application')->read_list('now');
		
	if(isset($_GET['name'])) {
		Log::write('By Name');
		
		$template = (!is_null(Registry::fetch('Interface')->template('Blog List Name'))) ? 
			Registry::fetch('Interface')->template('Blog List Name') : 
			'<li><a href="%2$s">%3$s</a></li>' ;
		
		$wrapper_template = (!is_null(Registry::fetch('Interface')->template('Blog List Name Wrapper'))) ? 
			Registry::fetch('Interface')->template('Blog List Name Wrapper') : 
			'<ul>%1$s</ul>' ;
		
		Log::write('Template :'.html_safe($template));
		Log::write('Master: '.html_safe($wrapper_template));
		
		# Parse the data array
		foreach($data as $item)
			$bit .= sprintf(
				$template, 
				$item['id'], #1
				Registry::fetch('Interface')->parse_link("ex://Blog/{$item['id']}"), #2
				$item['title'], #3
				$item['content'], #4
				$item['author'], #5
				$author->display_name, #6
				$author->profile_link,
				format_date($item['created']), #7
				format_date($item['modified']) #8
			);
		add(sprintf($wrapper_template, $bit));
		
	} else {
		Log::write('By Full');
		
		$template = (!is_null(Registry::fetch('Interface')->template('Blog List Item'))) ?
			Registry::fetch('Interface')->template('Blog List Item') :
			'<div style="margin-bottom:10px;"><h1>%3$s<div style="font-size:small;">Posted by <a href="%9$s">%10$s</a> on %6$s</div></h1><div>%4$s</div></div>' ;
		$wrapper_template = (!is_null(Registry::fetch('Interface')->template('Blog List Wrapper'))) ?
			Registry::fetch('Interface')->template('Blog List Item') :
			'%s' ;
			
		foreach($data as $item) {
			$author = new User($item['author']);
			$bit .= sprintf(
				$template,
				$item['id'], #1
				Registry::fetch('Interface')->parse_link("ex://Blog/{$item['id']}"), #2
				$item['title'], #3
				$item['content'], #4
				str_replace(',', ', ', $item['tags']), #5
				format_date($item['created'], $data_format), #6
				format_date($item['modified'], $date_format), #7
				$author->id, #8
				$author->display_name, #9
				$author->display_name #10
			);
		} add(sprintf($wrapper_template, $bit));
	}
	
} elseif($id == 'search') {
	Log::write('Blog: SEARCH');
	
	Registry::fetch('Interface')->error('Feature Not Supported', 'Sorry but this feature has yet to be implemented.');
	
} else { # Item View
	Log::write('Blog: ITEM');
	
	$data = Registry::fetch('Application')->read($id);
	$template = (Registry::fetch('Interface')->template('Blog Post')) ?
		Registry::fetch('Application')->template('Blog Post') :
		'<div><h1>%3$s<div style="font-size:small;">Posted by <a href="%9$s">%10$s</a> on %6$s</div></h1><div>%4$s</div></div>';
	
	$author = new User($data['author']);
	
	add(
		sprintf(
			$template,
			$data['id'], #1
			Registry::fetch('Interface')->parse_link("ex://Blog/{$data['id']}"), #2
			$data['title'], #3
			$data['content'], #4
			str_replace(',', ', ', $data['tags']), #5
			format_date($data['created'], $data_format), #6
			format_date($data['modified'], $date_format), #7
			$author->id, #8
			$author->profile_link, #9
			$author->display_name #10
		)
	);
	
}

/* Search */
Registry::fetch('Interface')->sidebar('div', 'title', 'Search', 'content', '<input disabled type="text" style="width:96%"/><br /><input type="button" value="Search &rarr;" style="width:96%"/><br /><span class="highlight">Search not yet implemented.</span>');

/* Write the archive links */
$month_number = date('m') + 1;
$year_number = date('Y') + 1;
for ($i=0; $i < Conf::read("Blog Number Archive Links"); $i++) { 
	$month_number = ($month_number > 01) ? $month_number - 1 : 12 ;
	$month_name = $months[$month_number];
	$year_number = ($month_number == 01) ? $year_number - 1 : $year_number ;
	$date_array[] = array('link' => Registry::fetch('Interface')->parse_link(sprintf("ex://Blog/Search?date&month=%s&year=%s", $month_number, $year_number)), 'name' => $month_name);
} Registry::fetch('Interface')->sidebar('menu', 'title', 'Archives', 'content', Registry::fetch('Interface')->menu($date_array, true));

/* By Comments */
/* By Views */