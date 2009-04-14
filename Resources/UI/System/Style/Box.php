<?php

header("Content-Type: text/css");
include 'Colorsheet.php';

echo <<<CSS

div.pagewrap {
	border-top: 30px solid $blue_dull;
	border-bottom: 30px solid $blue_dull;
}

div.lCol {
	text-align: center;
	float: none;
	width: auto;
}

div.lCol h1 {
	text-align: center;
	color: $blue_vivid;
	font-weight: normal;
	font-family: monospace;
	font-size: 20px;
}

div.lCol .UIFullError {
	text-align: center;
}

div.lCol .UIFullError h1 {
	margin: 10px 0 20px 0;
}

div.lCol .UIFullError .msg {
	background: #222;
	padding: 20px;
	margin: 15px 20px;
	border: 1px solid #333;
}

div.lCol hr {
	border: 1px dotted $blue_vivid;
}

div.UINotice,
div.UIError {
	background: #0F0F0F;
	width: 880px;
	margin: 5px auto;
	font-size: 13px;
	padding: 10px 5px;
}

div.UINotice {
	color: $blue_vivid;
	border-top: 1px solid $blue_dull;
	border-right: 5px solid $blue_dull;
	border-bottom: 1px solid $blue_dull;
	border-left: 5px solid $blue_dull;
}

div.UIError {
	color: $pink_vivid;
	border-top: 1px solid $pink_dull;
	border-right: 5px solid $pink_dull;
	border-bottom: 1px solid $pink_dull;
	border-left: 5px solid $pink_dull;
}

CSS;

?>