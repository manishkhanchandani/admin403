<?php
if($_POST['SubmitLOGIN']) {
	if($_COOKIE['superadminlogin']) {

	} else if($_POST['u']=="superadmin" && $_POST['p']=="superadmin123") {
		setcookie('superadminlogin',$_POST['u'],0,"/");
		header("Location: ".$_SERVER['PHP_SELF']);
		exit;
	} else {
		echo "<p class='errorMessage'>Please enter correct login details to enter.</p>";
		echo '<form name="form1" method="post" action="">User:<input name="u" type="text" id="u">Password:<input name="p" type="password" id="p"><input type="submit" name="SubmitLOGIN" value="Login"></form>
		';
		exit;
	}
} else {
	if($_COOKIE['superadminlogin']) {

	} else {
		echo '<form name="form1" method="post" action="">User:<input name="u" type="text" id="u">Password:<input name="p" type="password" id="p"><input type="submit" name="SubmitLOGIN" value="Login"></form>
		';
		exit;
	}
}
?>