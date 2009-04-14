<?php # Developer Signature Loader

function fetch_sig($id) {
	$id = crunch($id);
	$file = sprintf('%s/Resources/%s.php', dirname(__FILE__), $id);
	if(file_exists($file)) {
		include $file;
		return unserialize($author);
	} else {
		Log::write("fetch_sig($id) Developer signature does not exist.");
	}
}

function register_sig($id, $array) {
	$id = crunch($id);
	$template = "<?php # Developer ID [%s]\n\n\$author = '%s';\n\n?>";
	$resource = serialize($contents);
	if(isset($array['handle'])) $name = $array['handle'];
	else $name = $id;
	$write = sprintf($template, $name, serialize($array));
	FSWrite(sprintf('%s/Resources/%s.php', dirname(__FILE__), $id), $write);
	unset ($template, $$name, $array, $id);
}

