<?php

header("Content-Type: text/css");
include 'Colorsheet.php';

echo <<<CSS
																							/* Global */
* { 
	font-family: sans-serif, sans;
	color: #ddd;
	font-weight: normal;
}

body { 
	background: #191919 url('../Images/bg3.jpg') center center no-repeat fixed;
	margin: 42px 0 0 0;
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

a[name=top] {
	width: 0px;
	height: 0px;
	visibility: hidden;
	position: absolute;
	top: 0px;
}

pre {
	font-family: monospace;
	overflow: visible;
	text-align: left;
}

pre * {
	font-family: monospace;
}

pre.code,
code {
	overflow: visible;
	overflow-x: auto;
	margin: 10px;
	border: 1px solid #333;
	border-left: 5px solid #333;
	background: #222 URL('../Images/code-background.png') top right no-repeat;
	padding: 5px;
}

code {
}

pre.code *,
code * {
	font-family: monospace !important;
	font-size: 9px;
}

pre.log {
	font-size: 9px;
	overflow: visible;
	overflow-x: auto;
	margin: 10px;
	border: 1px solid #333;
	border-left: 5px solid #333;
	background: #222;
	padding: 5px;
}

pre.log * {
	font-family: monospace !important;
	font-size: 9px;
}

input {
	margin: 5px;
	border: 1px solid #333;
	background: #222;
	padding: 5px;
}

input:hover {
	background: #333;
	border: 1px solid #444;
}

input:focus {
	border: 1px solid #444;
	outline: #555 solid 1px;
}

																							/* Header */
.head { 
	background: #111 URL('../Images/bar.png') top center;
	height: 35px;
	top: 0px;
	position: fixed;
	width: 100%;
	border-bottom: 5px solid $blue_dull;
}

a.about {
	margin: 10px;
	display: block;
	font-size: 12px;
	float: left;
}

a.top {
	margin: 10px;
	display: block;
	font-size: 12px;
	float: right;
}

a.noshow,
a.noshow:hover {
	border: none;
	background: none;
	text-decoration: none;
}

.logo {
	padding: 6px 20px;
	border: none;
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
	padding: 10px 10px 5px 10px;
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

.leftNav li {
	margin-right: 5px;
}

.rightNav { 
	float: right;
}

.rightNav li {
	margin-left: 5px;
}
																							/* Nice Menu */
ul.niceMenu,
ul.UIMenu,
ul#twitter_update_list { 
	list-style-type: none;
	margin: 0;
	padding: 0;
	font-size: 10px;
}

ul.niceMenu a, 
ul.UIMenu a,
ul.niceMenu h2,
ul.UIMenu h2,
ul#twitter_update_list li { 
	width: 90%;
	padding: 7px 5%;
}

ul.niceMenu a,
ul.UIMenu a,
ul#twitter_update_list li {
	display: block;
	background-color: #000;
	background-image: url('../Images/Arrows/blue-small.png');
	background-position: center right;
	background-repeat: no-repeat;
	border-bottom: 1px dotted #191919;
	color: $blue_vivid;
}

ul.niceMenu a:hover,
ul.UIMenu a:hover,
ul#twitter_update_list li:hover {
	border-left: 1px solid $blue_vivid;
	background: $blue_dull;
	background-image: url('../Images/Arrows/blue-small.png');
	background-position: center right;
	background-repeat: no-repeat;
}

ul.niceMenu a:hover,
ul.UIMenu a:hover,
ul#twitter_update_list li:hover { 
	margin-left: -1;
}

ul.green a, 
ul.green h2,
span.green ul#twitter_update_list li { 
	color: $green_vivid;
} 

ul.green a,
ul.green a:hover {
	background-image: url('../Images/Arrows/green-small.png');
	background-position: center right;
	background-repeat: no-repeat;
}

ul.green a:hover, 
ul.green h2,
span.green ul#twitter_update_list li:hover { 
	background: $green_dull;
	border-left: 1px solid $green_vivid;
}

ul.blue a,
ul.UIMenu a,
ul.blue h2,
ul.UIMenu h2,
span.blue ul#twitter_update_list li:hover { 
	color: $blue_vivid;
} 

ul.blue a:hover,
ui.UIMenu a:hover,
ul.blue h2,
uiUIMenu h2,
span.blue ul#twitter_update_list li:hover { 
	background: $blue_dull;
	border-left: 1px solid $blue_vivid;
}

ul.blue a,
ul.blue a:hover {
	background-image: url('../Images/Arrows/blue-small.png');
	background-position: center right;
	background-repeat: no-repeat;
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

ul.pink a,
ul.pink a:hover {
	background-image: url('../Images/Arrows/pink-small.png');
	background-position: center right;
	background-repeat: no-repeat;
}

ul.niceMenu h2,
ul.UIMenu h2 { 
	display: block;
	border-bottom: 1px dotted #111;
	text-align: left;
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

div.lCol li,
div.lCol blockquote,
div.lCol pre,
div.lCol p,
div.lCol code,
div.lCol div,
div.lCol span { 
	color: #999;
	font-size: 13px;
}

blockquote {
	overflow: visible;
	overflow-x: auto;
	margin: 10px;
	border: 1px solid #333;
	border-left: 5px solid #333;
	background: #222 URL('../Images/quote-background.png') top right no-repeat;
	padding: 5px; 
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

div.lCol hr {
	color: #333;
}

span.meta { 
	color: #333;
	font-family: monospace;
	font-size: 10px !important;
}

span.meta:hover { 
	color: #eee;

}

div.postbody { 
	margin: 10px 0;
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
	border-left: 5px solid $blue_vivid;
	background: $blue_dull;
	color: $blue_vivid;
	padding: 5px 10px;
	font-size: 15px;
}

div.lCol h4 {
	border-bottom: 1px solid #eee;
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

.rCol .UISidebar {
	margin-bottom: 10px;
}

.rCol .UISidebar .title { 
	font-weight: normal;
	font-family: monospace;
	font-size: 15px;
	background: #000;
	color: $blue_vivid;
	border-bottom: 5px solid $blue_dull;
	padding: 7px 5%;
	margin: 5px 0 0 0;
}

.rCol .UISidebar .content { 
	background: black;
	margin-bottom: 10px;
	padding: 5px;
}

.rCol .UISidebar .content .UIError,
.rCol .UISidebar .content .UINotice {
	border-bottom: 1px dotted #333;
	padding: 5px;
}

.rCol .UISidebar img.sidebar-image {
	width: 96%;
	height: auto;
	border: none;
}

.rCol .UISidebar a.sidebar-image-link,
.rCol .UISidebar a.sidebar-image-link:hover {
	background: none;
	margin: 0;
	padding: 0;
}

.rCol .UISidebar img.sidebar-image {
	padding: 2%;
}

.rCol .UISidebar a.sidebar-image-link img {
	background: #000;
	padding: 2%;
}

.rCol .UISidebar a.sidebar-image-link:hover img {
	background: $blue_dull;
}
																							/* Footer */
.footer { 
	text-align: left;
	width: 900px;
	background: black;
	margin: 10px auto;
	padding: 0px;
	font-size: 10px;
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
	background: none;
	border: none;
}

.footer li.footer-about a:hover {
	background: none;
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
	height: 300px;
}
																		/* Special */
.clear { 
	clear: both;
	height: 0px;
	overflow: hidden;
	margin: 0;
}

.highlight {
	background: $blue_vivid;
	color: black;
	padding: 1px;
}

CSS;


?>
