<?php

header("Content-Type: text/css");

$background = '#607c83';
$base = '#a9ccdc';
$highlight1 = '#88a6b1';
$highlight2 = '#248bb1';
$text = 'black';
$readable = 'white';

echo <<<CSS
body { font-family: sans-serif; background: $background; margin: 20px; }
a { color: $highlight2; text-decoration: none; }
table, tr, td, tbody { text-align: left; }
a:hover { color: $background; }	
input, textarea { padding: 5px; width: 98%; border: 1px solid #999; }
input[type=text]:focus, input[type=password]:focus, textarea:focus { outline: $base solid 2px; border: 1px solid $highlight2; }
input[type=submit], input[type=button], input[type=reset] { background: #ddd; padding: 4px; width: auto; }
input[type=submit]:hover, input[type=button]:hover, input[type=reset]:hover { background: #ccc; }
textarea { height: 300px; }
.main { width: 900px; background: $readable; }
.head { height: 160px; background: URL('true_logo.jpg') center top no-repeat; cursor: pointer; }
.nav { text-align: center; background: $highlight1; padding: 10px 0; }
.nav ul { margin: 0; padding: 0; }
.nav li { display: inline; }
.nav a { padding: 10px 20px; text-decoration: none; color: $readable; font-weight: bold; padding: 10px 20px; }
.nav a:hover { background: $highlight2; color: $readable; } 
.submenu { background-color: $base; height: 30px; font-size: 15px; }
.submenu table { width: 100%; height: 30px !important; }
.submenu td { padding: 5px; }
.submenu ul { padding: 0; margin: 0; }
.submenu li { display: inline; }
.submenu td.left li { margin-right: 5px; }
.submenu td.left li { margin-right: 2px; margin-left: 2px; }
.submenu td.left li { margin-left: 5px; }
.submenu li a, .submenu a { color: $highlight2; padding: 5px 10px; }
.submenu li a:hover, .submenu a:hover { color: $readable; background-color: $highlight2; }
.submenu td.left { text-align: left; }
.submenu td.center { text-align: center; }
.submenu td.right { text-align: right; }
.sidebar { width: 300px; background: $base; font-size: 12px; }
.sidebar div.item { margin: 5px 5px 20px 5px; }
.sidebar h1 { margin: 0 0 5px 0; font-size: 20px; }
.sidebar ul { margin-left: -20px; }
.sidebar .more { font-size: small; text-align: right; margin-top: 5px; }
.UIError, .UINotice, .UIError, .UINotice { border: 2px solid #999; padding: 5px; margin-top: 15px; font-size: small; line-height: 20px; }
.UIError, .UIError { background: #FCF3AA; } .UINotice, .UINotice { background: #ccc; }
.content { width: 580px; padding: 10px; }
.content h1 { font-size: 30px; }
.content img { border: 1px solid $highlight2; padding: 5px; }
.content img.left { float: left; }
.content img.right { float: right; }
.content h2 { font-size: 20px; }	
.content table { width: 100%; margin-bottom: 10px; }
.footer { height: 70px; background: $highlight1 URL('true_footer.jpg') center bottom no-repeat; text-align: center; padding-top: 7px; font-size: 10px; }
.footer a { color: $text; text-decoration: underline; }
.footer a:hover { color: $highlight2; }
/* Other Stuffs */
.EXFeaturedVideos img, .EXImagesTiles img { padding: 2px; margin: 3px; border: 2px solid $highlight2; }
.EXFeaturedVideos img:hover, .EXImagesTiles img:hover { border: 2px solid #333; }
.EXVideos {  }
.EXVideosArtwork { padding-right: 5px; }
.EXVideosArtwork img { padding: 0px; border: none; }
.EXVideosName:hover { color: $highlight2; }
.EXVideosDesc {	}
.EXVidesoFeatureName { text-align: center; margin: 5px 0; }
.EXVideosFeaturePresentation{ text-align: center; margin-bottom: 10px; }
.EXVideosFeatureNav, .EXImagesNav { cursor: pointer; padding: 3px 5px; }
.EXVideosFeatureNav:hover, .EXImagesNav:hover { color: $readable; background-color: $highlight2; }
.EXVideosFeatureDescription, .EXImagesDescription { border: 1px dashed #333; background-color: #ddd; margin: 10px; padding: 10px; }
.EXVideosFeatureDescription h2 { font-size: 15px; }
.EXVideosFeatureDescription p { font-size: 13px; }
.EXVideosFeatureDescription i { font-size: 12px; }
.EXVideosFeatureGallery { margin: 10px; }
.EXImagesPresentation { text-align: center; }
.EXImagesTiles { text-align: center; }
.EXImagesNavSide { cursor: pointer; padding: 5px 5px; width: 97%; display: block; font-size: 15px; }
.EXImagesNavSide:hover { color: $readable; background-color: $highlight2; }
.EXPagesList, .EXVideosList, .EXVideosPage, .EXNewsList, .EXLinksList { background: #eee; border: 2px solid #999; cursor: pointer; }
.EXPagesList .title, .EXVideosList .title { padding: 5px; font-size: 20px; font-weight: bold; }
.EXPagesList .actions, .EXVideosList .actions { text-align: right; font-size: 13px; padding: 5px; width: 50px }
.EXPagesList .preview, .EXVideosList .preview { border-top: 1px solid #999; border-bottom: 1px solid #999; padding: 5px; font-size: 15px; }
.EXPagesList .meta, .EXVideosList .meta { padding: 5px; font-size: 13px; }
.EXVideosList .artwork { border-top: 1px solid #999; border-bottom: 1px solid #999; padding: 5px; width: 100px }
.EXVideosList .artwork img { border: none; margin: 0; padding: 0; height: 100%; width: 100%; }
.EXVideosPage .artwork { padding: 5px; width: 100px; height: 100px; border-right: 1px solid #999; }
.EXVideosPage .artwork img { border: none; margin: 0; padding: 0; height: 100%; width: 100%; }
.EXVideosPage .title { padding: 5px; font-size: 20px; font-weight: bold; }
.EXVideosPage .preview { border-top: 1px solid #999; padding: 5px; font-size: 13px; }
.EXNewsList .date { border-bottom: 1px solid #999; padding: 5px; font-weight: bold; font-size: 20px; }
.EXNewsList .content, .EXLinksList .content { font-size: 13px; padding: 5px; border-right: 1px solid #999; border-bottom: 1px solid #999; }
.EXNewsList .delete, .EXLinksList .delete { padding: 5px; border-bottom: 1px solid #999; cursor: pointer; }
.EXNewsList .delete:hover, .EXLinksList .delete:hover { background: #ccc; color: #f00; }
CSS;

?>
