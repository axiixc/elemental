<?php

function import($id) {
	$use_id = crunch($id);
	$file = sprintf('%s/Resources/%s.php', dirname(__FILE__), $use_id);
	if(file_exists($file)) {
		include $file;
		if($resource_type == 'array') return unfold($resource);
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
		$resource = fold($contents);
	} else { # string
		$resource_type = 'string';
		$resource = $contents;
	} $write = sprintf($template, $use_id, $resource_type, $resource);
	FSWrite(sprintf('%s/Resources/%s.php', dirname(__FILE__), $use_id), $write);
	unset ($template, $resource_type, $resource, $use_id);
}