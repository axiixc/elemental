<?php # AXIIXC Theme Templates [axiixc]

$templates['a-image-with-head'] = '<div class="UISidebar" id="SB%4$s"><div class="title">%1$s</div><a href="%3$s" class="sidebar-image-link"><img src="%2$s" class="sidebar-image" /></a></div>';
$templates['a-image-without-head'] = '<div class="UISidebar" id="SB%3$s"><a href="%2$s;" class="sidebar-image-link"><img src="%1$s" class="sidebar-image" /></a></div>';
$templates['diagnostic-false'] = '<span style="color:red">FALSE</span>';
$templates['diagnostic-item'] = '<span style="color:#2C68C1;">[%s]</span>&nbsp;%s<br />';
$templates['diagnostic-null'] = '<span style="color:yellow">NULL</span>';
$templates['diagnostic-true'] = '<span style="color:green">TRUE</span>';
$templates['diagnostic'] = '<pre class="log">%s</pre>';
$templates['div-with-head'] = '<div class="UISidebar" id="SB%3$s"><div class="title">%1$s</div><div class="content">%2$s</div></div>';
$templates['div-without-head'] = '<div class="UISidebar" id="SB%2$s"><div class="content">%1$s</div></div>';
$templates['image-with-head'] = '<div class="UISidebar" id="SB%3$s"><div class="title">%1$s</div><img src="%2$s" class="sidebar-image" /></div>';
$templates['image-without-head'] = '<div class="UISidebar" id="SB%2$s"><img src="%1$s" class="sidebar-image" /></div>';
$templates['log-message'] = '[%1$03s] %3$s<br />';
$templates['log'] = '<pre class="log">%1$s</pre>';
$templates['login-window'] = <<<EOD
<form method="post" action="[[LOGINPATH]]">
	<table style="margin:0 auto;">
		<tr>
			<td><input type="text" name="UAU" value="Username" onfocus="if(this.value==this.defaultValue){this.value=''};" /></td>
			<td<input type="checkbox" name="extended" id="ext" /> <label for="ext">Remember Me</label></td>
		</tr><tr>
			<td><input type="password" name="UAP" value="xxxxxxxx" onfocus="if(this.value==this.defaultValue){this.value=''};" /></td>
			<td><input type="submit" value="Login" /></td>
		</tr>
	</table>
</form>
EOD;
$templates['menu-item'] = '<li%3$s><a href="%1$s"%3$s>%2$s</a></li>';
$templates['menu-post'] = '</ul>';
$templates['menu-pre'] = '<ul class="UIMenu">';
$templates['menu-with-head'] = '<div class="UISidebar" id="SB%3$s"><div class="title">%1$s</div>%2$s</div>';
$templates['menu-without-head'] = '<div class="UISidebar" id="SB%2$s">%1$s</div>';
$templates['notification-error'] = '<div class="UIFullError"><h1>%1$s</h1><div class="msg">%2$s</div></div>';
$templates['notification-uierror'] = '<div class="UIError">%1$s</div>';
$templates['notification-uinotice'] = '<div class="UINotice">%1$s</div>';
$templates['page-list-item'] = '<div><a href="%2$s">%3$s</a> <span class="meta">by <a href="%8$s">%9$s</a> on %6$s</span><div class="postbody">%4$10.300s</div></div><hr />';
$templates['page-item'] = '<h1>%2$s</h1><span class="meta">Posted by <a href="%8$s">%9$s</a> on %5$s [created %4$s]</span><hr /><div class="postbody">%3$s</div>';