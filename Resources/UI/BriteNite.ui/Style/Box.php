<?php

header("Content-Type: text/css");
include '../Conf.php';

echo <<<CSS

div.pagewrap {
	border-top: 30px solid $blue_dull;
	border-bottom: 30px solid $blue_dull;
}

div.lCol {
	width: 99%;
	text-align: center;
}

div.lCol h1 {
	text-align: center;
	color: $blue_vivid;
	font-weight: normal;
	font-family: monospace;
}

div.footer {
	height: auto;
	padding: 10px;
	text-align: center;
	width: 880px;
	border-top: 5px solid $green_dull;
}

CSS;

?>