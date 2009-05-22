<?php # Hardfile Management [axiixc:chuck]

function import($id) {
	$use_id = crunch($id);
	$file = sprintf('%s/Resources/%s.php', dirname(__FILE__), $use_id);
	if(file_exists($file)) {
		include $file;
		$resource = str_replace('[%"%]', '\'', $resource);
		if($resource_type == 'array') return unserialize($resource);
		else return $resource;
	} else {
		Log::write("import($id) Resource does not exist.");
	}
}

function register_resource($id, $contents) {
	$use_id = crunch($id);
	$template = "<?php # %s : System Resource\n\n\$resource_type = '%s';\n\$resource = '%s';\n\n?>";
	if(is_array($contents)) {
		$resource_type = 'array';
		$resource = serialize($contents);
	} else { # string
		$resource_type = 'string';
		$resource = $contents;
	} $resource = str_replace('\'', '[%"%]', $resource);
	$write = sprintf($template, uncrunch($use_id), $resource_type, $resource);
	FSWrite(sprintf('%s/Resources/%s.php', dirname(__FILE__), $use_id), $write);
	unset ($template, $resource_type, $resource, $use_id);
}

function append_resource($id, $contents) {
	$temp = import($id);
	$temp[] = $contents;
	register_resource($id, $temp);
}