<?php # login_page : System Resource

$resource_type = 'string';
$resource = <<<FORM
	<form method="post">
		<input type="text" name="UAU" value="Username" onfocus="if(this.value==this.defaultValue){this.value=''};" />
		<input type="password" name="UAP" value="xxxxxxxx" onfocus="if(this.value==this.defaultValue){this.value=''};" />
		<input type="submit" value="Login" /><!-- JS: EXAutoClear(); -->
	</form>
FORM

?>
