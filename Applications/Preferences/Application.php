<?php # Preference Page Loader Application [axiixc]

$page = ($_GET['arg'] != null) ? $_GET['arg'] : 'system' ;
$pages = FSDirRead(root.'Resources/Preferences', true, lower_case_filename);

foreach($pages as $id => $path) {
	$id = crunch($id);
	if(file_exists($path.'Info.php')) include $path.'Info.php';
	$name = (is_null($preference['name']) or $preference['name'] == null) ? uncrunch($id) : $preference['name'] ;
	$pref_pages[] = array('name' => $name, 'link' => parse_link("ex://Preferences/".uncrunch($id)));
	unset($preference);
} sidebar('menu', 'title', 'Preferences', 'content', menu($pref_pages, true));

if(file_exists($pages[$page].'Info.php')) include $pages[$page].'Info.php';
if(file_exists($app_list[crunch($preference['app_link'])].'Resources.php')) {
	include $app_list[crunch($preference['app_link'])].'Resources.php';
	$crunched_id = crunch($preference['app_link']);
	Registry::register('Handler', new $crunched_id);
}

title((is_null($preference['name'] or $preference['name'] != null)) ? $preference['name'] : uncrunch($_GET['arg']));

if(file_exists($pages[$page].'Preference.php')) include $pages[$page].'Preference.php';
else error("Invalid Preference Bundle", "The preference bundle is invalid and cannot be used.");

# Runeach Actions Below Here #
#           <(^^,)>          #
# Runeach Actions Above Here #