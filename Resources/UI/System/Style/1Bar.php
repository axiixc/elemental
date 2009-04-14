<?php

header("Content-Type: text/css");
include 'Colorsheet.php';

echo <<<CSS

div.lCol {
	float: none;
	width: auto;
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