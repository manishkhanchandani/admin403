<?php require_once('../Connections/dw_conn.php'); ?>
<?php
include('start.php');
if($_POST['MM_Insert']==1) {
	$result = $Common->login($_POST['action'], $_POST);
	switch($_POST['action']) {
		case 'admin':
			$url = HTTPPATH."/admin/admin_report.php?menuTopItem=1";
			/*
			$sql = "select admin_id, email, name from admin where email = '".addslashes(stripslashes(trim($_POST['email'])))."' and password = '".md5($_POST['password'])."'";
			$rs = mysql_query($sql) or die('error');
			if(mysql_num_rows($rs)) {
				$rec = mysql_fetch_array($rs);
				setcookie("admin[admin_id]",$rec['admin_id'],0,"/");
				setcookie("admin[email]",$rec['email'],0,"/");
				setcookie("admin[name]",$rec['name'],0,"/");
				if($_GET['redirect_url']) {
					header("Location: ".urldecode($_GET['redirect_url']));
					exit;
				}
				header("Location: ".HTTPPATH."/admin/admin_report.php?menuTopItem=1");
				exit;
			} else {
				$error = 'Email and Password does not match with our record.';
			} 
			*/
			break;
		case 'vendor':
			$url = HTTPPATH."/vendor/actions.php";
			/*
			$sql = "select vendor_id, email, name from vendor where email = '".addslashes(stripslashes(trim($_POST['email'])))."' and password = '".md5($_POST['password'])."'";
			$rs = mysql_query($sql) or die('error');
			if(mysql_num_rows($rs)) {
				$rec = mysql_fetch_array($rs);
				setcookie("vendor[vendor_id]",$rec['vendor_id'],0,"/");
				setcookie("vendor[email]",$rec['email'],0,"/");
				setcookie("vendor[name]",$rec['name'],0,"/");
				if($_GET['redirect_url']) {
					header("Location: ".urldecode($_GET['redirect_url']));
					exit;
				}
				header("Location: ".HTTPPATH."/vendor/actions.php");
				exit;
			} else {
				$error = 'Email and Password does not match with our record.';
			} 
			*/
			break;
		case 'employer':
			$url = HTTPPATH."/employer/actions.php";
			/*
			$sql = "select employer_id, email, name from employer where email = '".addslashes(stripslashes(trim($_POST['email'])))."' and password = '".md5($_POST['password'])."'";
			$rs = mysql_query($sql) or die('error');
			if(mysql_num_rows($rs)) {
				$rec = mysql_fetch_array($rs);
				setcookie("employer[employer_id]",$rec['employer_id'],0,"/");
				setcookie("employer[email]",$rec['email'],0,"/");
				setcookie("employer[name]",$rec['name'],0,"/");
				if($_GET['redirect_url']) {
					header("Location: ".urldecode($_GET['redirect_url']));
					exit;
				}
				header("Location: ".HTTPPATH."/employer/actions.php");
				exit;
			} else {
				$sql = "select e.employer_id from employee as e, users as u where e.employee_id = u.id and e.email = '".addslashes(stripslashes(trim($_POST['email'])))."' and e.password = '".md5($_POST['password'])."' and u.acting_as = 'Employer'";
				$rs = mysql_query($sql) or die('error');
				if(mysql_num_rows($rs)) {
					$rec = mysql_fetch_array($rs);
					$employer_id = $rec['employer_id'];
					$sql = "select employer_id, email, name from employer where employer_id = '".$employer_id."'";
					$rs = mysql_query($sql) or die('error');
					$rec = mysql_fetch_array($rs);
					setcookie("employer[employer_id]",$rec['employer_id'],0,"/");
					setcookie("employer[email]",$rec['email'],0,"/");
					setcookie("employer[name]",$rec['name'],0,"/");
					if($_GET['redirect_url']) {
						header("Location: ".urldecode($_GET['redirect_url']));
						exit;
					}
					header("Location: ".HTTPPATH."/employer/actions.php");
					exit;
				} else {
					$sql = "select e.employer_id from employer_access as e, users as u where e.compliance_id = u.id and e.compliance_designee_email = '".addslashes(stripslashes(trim($_POST['email'])))."' and e.compliance_designee_password = '".md5($_POST['password'])."' and u.login_type = 'Designee' AND u.acting_as = 'Employer'";
					$rs = mysql_query($sql) or die('error');
					if(mysql_num_rows($rs)) {
						$rec = mysql_fetch_array($rs);
						$employer_id = $rec['employer_id'];
						$sql = "select employer_id, email, name from employer where employer_id = '".$employer_id."'";
						$rs = mysql_query($sql) or die('error');
						$rec = mysql_fetch_array($rs);
						setcookie("employer[employer_id]",$rec['employer_id'],0,"/");
						setcookie("employer[email]",$rec['email'],0,"/");
						setcookie("employer[name]",$rec['name'],0,"/");
						if($_GET['redirect_url']) {
							header("Location: ".urldecode($_GET['redirect_url']));
							exit;
						}
						header("Location: ".HTTPPATH."/employer/actions.php");
						exit;
					} else {
						$error = 'Email and Password does not match with our record.';
					}
				}
			} 
			*/
			break;
		case 'employee':
			$url = HTTPPATH."/employee/summary.php?menuTopItem=4";
			/*
			$sql = "select * from employee where email = '".addslashes(stripslashes(trim($_POST['email'])))."' and password = '".md5($_POST['password'])."'";
			$rs = mysql_query($sql) or die('error');
			if(mysql_num_rows($rs)) {
				$rec = mysql_fetch_array($rs);
				setcookie("employee[employee_id]",$rec['employee_id'],0,"/");
				setcookie("employee[email]",$rec['email'],0,"/");
				setcookie("employee[name]",$rec['firstname'].' '.$rec['lastname'],0,"/");
				if($_GET['redirect_url']) {
					header("Location: ".urldecode($_GET['redirect_url']));
					exit;
				}
				header("Location: ".HTTPPATH."/employee/summary.php?menuTopItem=4");
				exit;
			} else {
				$error = 'Email and Password does not match with our record.';
			}
			*/ 
			break;
	}	
	if($result == 1) {
		if($_GET['redirect_url']) {
			header("Location: ".urldecode($_GET['redirect_url']));
			exit;
		}
		header("Location: ".$url);
		exit;
	} else {
		$error = 'Email and Password does not match with our record.';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<style type="text/css">
<!--
body,td,th,input,select,submit,button,textarea,div {
	font-family: Tahoma;
	font-size: 11px;
	margin:0px;
	padding:0px;
}
.error {
	font-weight: bold;
	color: #FF0000;
}
body {
	background-color: #808080;
}
-->
</style>
</head>

<body><!-- #BeginLibraryItem "/Library/headerframe.lbi" --><iframe src="<?php echo HTTPPATH; ?>/Templates/BlankTemplateAM/index.php" name="headerverityfrm" width="100%" height="125px" scrolling="No" frameborder="0" id="headerverityfrm"></iframe><!-- #EndLibraryItem --><table width="100%" border="6" cellspacing="0" cellpadding="3" bordercolor="#404040" style="border-style:solid">
    <tr valign="bottom" >
        <td colspan="2" align="CENTER" bgcolor="#404040" height="15">
       	 <strong><font color="#ffffff" face="Tahoma" size="2">Login</font></strong>        </td>
    </tr>
    <tr valign="bottom" bgcolor="#FFFFFF" >
      <td colspan="2" align="CENTER" height="15"><form id="form1" name="form1" method="post" action="">
      <?php if($error) { ?>
	  	<p class="error"><?php echo $error; ?></p>
      <?php } else if($_GET['errorMessage']) { ?>
	  	<p class="error"><?php echo $_GET['errorMessage']; ?></p>
	  <?php } ?>
        <table border="0" cellspacing="1" cellpadding="5">
          <tr>
            <td align="right">Email:</td>
            <td align="left"><input name="email" type="text" id="email" size="35" /></td>
          </tr>
          <tr>
            <td align="right">Password:</td>
            <td align="left"><input name="password" type="password" id="password" size="15" /></td>
          </tr>
          <tr align="center">
            <td colspan="2"><input name="action" type="radio" id="radio" value="admin" <?php if($_GET['check']=="admin" || !$_GET['check']) { ?>checked="checked"<?php } ?> />
              Administrator
                <input type="radio" name="action" id="radio2" value="employer" <?php if($_GET['check']=="employer") { ?>checked="checked"<?php } ?> />
                Employer 
                <input type="radio" name="action" id="radio3" value="employee" <?php if($_GET['check']=="employee") { ?>checked="checked"<?php } ?> /> 
                Employee 
                <input type="radio" name="action" id="radio4" value="vendor"<?php if($_GET['check']=="vendor") { ?>checked="checked"<?php } ?> /> 
                Vendor
            </td>
          </tr>
          <tr>
            <td align="left">&nbsp;</td>
            <td align="left"><input type="submit" name="button" id="button" value="Login" />
            <input name="MM_Insert" type="hidden" id="MM_Insert" value="1" /></td>
          </tr>
          <tr align="center">
            <td colspan="2"><a href="forgot.php">Forgot Password</a> </td>
          </tr>
          <tr>
            <td colspan="2" align="left"><hr /></td>
          </tr>
          <tr>
            <td colspan="2" align="left"><table width="100%" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td align="center"><a href="../employee/employee_add.php">Register As Employee</a></td>
                </tr>
            </table></td>
          </tr>
        </table>
            </form>
      </td>
    </tr>
</table>
</body>
</html>
