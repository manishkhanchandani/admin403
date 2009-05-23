<?php
include_once('start.php');
?>
<?php
if(!$_COOKIE['employer']['employer_id']) {
$errorMessage = "Please login as employer";
$check = "employer";
?>
<script language="javascript">
	location.href="<?php echo HTTPPATH; ?>/main/login.php?errorMessage=<?php echo urlencode($errorMessage); ?>&check=<?php echo $check; ?>&redirect_url=<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo urlencode($_SERVER['QUERY_STRING']); ?>";
</script>
<?php
}
$MAINHEADING = $_COOKIE['employer']['name'];
?>