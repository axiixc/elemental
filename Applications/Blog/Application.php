<?php # Blog [axiixc] : Basic blog application

$id = (isset($_GET['arg'])) ? mysql_safe($_GET['arg']) : Conf::read("Pages Default");
if($id == crunch('list')) { # List View
	$list = $app->list()
} else { # Item View
	
}