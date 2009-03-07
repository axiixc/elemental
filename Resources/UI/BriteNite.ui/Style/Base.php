<?php

header("Content-Type: text/css");
include '../Conf.php';

echo <<<CSS
																							/* Global */
* { 
	font-family: sans-serif, sans;
	color: #ddd;
	font-weight: normal;
}

body { 
	background: #191919 URL('../Images/bg3.jpg') center center no-repeat fixed;
	margin: 0;
	padding: 0;
	text-align: center;
}

b {
	font-weight: bold;
}

a { 
	color: $blue_vivid;
	background: $blue_dull;
	font-family: monospace;
	text-decoration: none;
	padding: 1px 3px;
}

a:hover { 
	color: $green_vivid;
	background: $green_dull;
}

h1 { 
	font-size: 20px;
}
																							/* Header */
.head { 
	background: #111 URL('../Images/bar.png') top right;
	height: 35px;
	margin: 0px;
}

.logo {
	padding: 5px;
	cursor: pointer;
}

.logo:hover { 
	background: $blue_dull;
}
																							/* Top Navigation */
.nav { 
	height: 25px;
	text-align: left;
	width: 900px;
	margin: 2px auto 10px auto;
}

.nav ul { 
	margin: 0;
	padding: 0;
}

.nav li { 
	display: inline;
}

.nav a { 
	padding: 5px 10px 5px 10px;
	border-bottom: 5px solid $pink_dull;
	color: $pink_vivid;
	background: #111;
	outline-bottom: black solid 5px;
}

.nav a:hover { 
	border-bottom: 10px solid $blue_dull;
	color: $blue_vivid;
}

.nav a.current { 
	border-bottom: 15px solid $blue_dull;
	color: $blue_vivid;
}

.leftNav { 
	float: left;
}

.rightNav { 
	float: right;
}
																							/* Nice Menu */
ul.niceMenu,
ul#twitter_update_list { 
	list-style-type: none;
	margin: 0;
	padding: 0;
	font-size: 10px;
}

ul.niceMenu a, 
ul.niceMenu h2,
ul#twitter_update_list li { 
	width: 90%;
	padding: 7px 5%;
}

ul.niceMenu a,
ul#twitter_update_list li { 
	display: block;
	background: #000;
	border-bottom: 1px dotted #191919;
	color: $blue_vivid;
}

ul.niceMenu a:hover,
ul#twitter_update_list li:hover {
	border-left: 1px solid $blue_vivid;
	background: $blue_dull;
}

ul.niceMenu a:hover,
ul#twitter_update_list li:hover { 
	margin-left: -1;
}

ul.green a, 
ul.green h2,
span.green ul#twitter_update_list li { 
	color: $green_vivid;
} 

ul.green a:hover, 
ul.green h2,
span.green ul#twitter_update_list li:hover { 
	background: $green_dull;
	border-left: 1px solid $green_vivid;
}

ul.blue a, 
ul.blue h2,
span.blue ul#twitter_update_list li:hover { 
	color: $blue_vivid;
} 

ul.blue a:hover, 
ul.blue h2,
span.blue ul#twitter_update_list li:hover { 
	background: $blue_dull;
	border-left: 1px solid $blue_vivid;
}

ul.pink a, 
ul.pink h2,
span.pink ul#twitter_update_list li { 
	color: $pink_vivid;
}

ul.pink a:hover, 
ul.pink h2,
span.pink ul#twitter_update_list li:hover { 
	background: $pink_dull;
	border-left: 1px solid $pink_vivid;
}

ul.niceMenu h2 { 
	display: block;
	border-bottom: 1px dotted #111;
	text-align: right;
	font-family: Monaco, monospace;
	font-size: 12px;
	margin: 0;
	font-weight: bold;
	border: none;
}
																							/* Content */
.pagewrap { 
	text-align: left;
	width: 900px;
	border-top: 5px solid $blue_dull;
	border-bottom: 5px solid $blue_dull;
	background: #111;
	margin: 0 auto;
	font-size: 13px;
}
																							/* Left Col */
div.lCol { 
	width: 71%;
	float: left;
	margin: 0;
	padding: 10px;
	line-height: 1.3em;
}

div.lCol * { 
	color: #999;
	font-size: 13px;
}

div.lCol h1 { 
	padding: 0;
	margin: 0 0 5px 0;
}

div.lCol h3 {
	text-align: center;
}

div.lCol h1 a, 
.meta a, 
a.readmore { 
	color: $blue_vivid;
	background: $blue_dull;
}

div.lCol h1 a:hover,
.meta a:hover, 
a:hover.readmore  { 
	color: $green_vivid;
	background: $green_dull;
}

span.meta { 
	color: #333;
	font-family: monospace;
	font-size: 10px;
}

span.meta:hover { 
	color: #eee;

}

div.postbody { 
	margin: 5px 0;
	border-top: 1px solid #333;
	line-height: 1.3em;
}

div.postbody blockquote { 
	background: #191919;
	margin: 10px;
	padding: 10px 10px 10px 30px;
}

div.postbody a { 
	font-size: 12px;
}

div.lCol h2 {
	font-family: monospace;
	border-left: 5px solid $blue_vivid;
	background: $blue_dull;
	color: $blue_vivid;
	padding: 5px 10px;
	font-size: 15px;
}

a.readmore { 
	float: right;
	font-size: 10px;
	margin-top: -15px;
}
																							/* Right Col */
.rCol {
	padding: 0 5px;
	margin-left: 73%;
	font-size: 90%;
}

* html .rCol {  /* prevents IE 3-px shift next to a float */
	height: 1%;
}

.rCol h1, 
.rCol h2 { 
	font-weight: normal;
	font-family: monospace;
	font-size: 15px;
	background: #000;
	color: $blue_vivid;
	border-bottom: 5px solid $blue_dull;
	padding: 7px 5%;
	margin-bottom: 0;
}

.rCol div.item { 
	background: black;
	margin-bottom: 10px;
}

.rCol div.item div {
	padding: 5px;
}
																							/* Twitter Fix */
.rCol div.item div#twitter_div {
	padding: 0;
}

.rCol div.box { 
	background: black;
	padding: 5px;
}
																							/* Footer */
.footer { 
	text-align: left;
	width: 900px;
	background: black;
	margin: 10px auto;
	font-size: 10px;
	height: auto;
	border-bottom: 5px solid $green_dull;
}

.col1 { 
	width: 50%;
	float: left;
}

.col2, .col3 { 
	width: 25%;
	float: left;
}
																							/* Some special footer stuff */
.footer-special img { 
	float: left;
	margin: 5px;
}

.footer-special p { 
	margin: 0;
	padding: 0;
	color: #333;
	line-height: 1.5em;
	padding: 5px;
}

li.footer-special:hover p { 
	color: #eee;
}

.footer li.footer-about a img,
.footer li.footer-about a {
	border: none;
}

.footer li.footer-about a:hover {
	background: #999;
	border: none;
}

.footer li.footer-about:hover {
	margin: 0px;
}

.footer li.footer-about:hover {
	border: none;
}

.footer ul.niceMenu {
	background: black;
}
																							/* Special */
.clear { 
	clear: both;
	height: 1px;
	overflow: hidden;
	margin: 0;
}

CSS;


?>
