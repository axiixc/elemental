<?php
$dir = EXScriptDir();
if(!function_exists('EXNewsRead')) include(EXApplication('News'));
if(!function_exists('EXVideosFeatured')) include(EXApplication('Videos'));
if(!function_exists('EXGoogleAnalytics')) include(EXPackage('GoogleAnalytics'));
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<title><?php UIMetaHeadTitle(); ?></title>
		<link rel="stylesheet" type="text/css" href="Resources/UI/Default.ui/style.php" />
		<link rel="shortcut icon" href="favicon.png" type="image/x-icon" /> 
		<?php
			UIMetaHeadBlock();
			UIJavascript();
		?>
		<!--[if IE]>
			<style type="text/css">
			a { display: inline; }
			.sidebar ul { margin-right: 0px; margin-left: 20px; }
			</style>
		<![endif]-->
	</head>

	<body<?php UIJavascriptOnload(); ?>>
		<center>
			<table class="main" cellspacing="0">
				<!-- head -->
				<tr><td class="head" colspan="2" onclick="window.location='index.php';">&nbsp;</td></tr>
				<!-- main navigation -->
				<tr><td class="nav" colspan="2"><?php UIMenu('Main'); ?></td></tr>
				<tr>
					<!-- sub nanigation -->
					<td valign="top" class="submenu"><?php UISubmenu(); ?></td>
					<!-- sidebar -->
					<td valign="top" class="sidebar" rowspan="2">
						<?php UISidebar(); ?>
						<?php if(UINotificationCount(UINotification) > 0) {
							echo "<div class=\"item\">";
							UINotification(UINotification);
							echo "</div>";
						} ?>
						<!-- Custom Sidebars -->
						<?php if($EXFeaturedVideos != 'OFF') { ?>
						<div class="item">
							<center style="margin-top: 7px;"><?php echo EXVideosFeatured(4); ?></center>
							<div class="more"><a href="?app=Videos">All Videos &rarr;</a></div>
						</div>
						<?php } if($EXNewsFeed != 'OFF') { ?>
						<div class="item">
							<?php echo EXNewsRead(10); ?>
							<div class="more"><a href="?app=News">Archives &rarr;</a></div>
						</div>
						<?php } ?>
						<!-- End Custom Sidebars -->
					</td>
				</tr>
				<tr><td valign="top" class="content"><?php UIContent(); ?></td></tr>
				<tr><td colspan="2" class="footer" valign="top"><?php UIMetaFooter(); ?></td></tr>
			</table>
		</center>
		<div style="visibility:hidden;width:0px;height:0px;position:absolute;top:-5;" id="EXJSActionFrame"></div>
		<?php EXGoogleAnalytics(); ?>
	</body>
</html>
