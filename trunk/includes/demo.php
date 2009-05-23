<?php 
include('start.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<meta name="keywords" content="<?php echo METAKEYWORDS; ?>" />
<meta name="description" content="<?php echo METADESCRIPTION; ?>" />
<?php
// inside head
include('css.php');
include('js.php');
?>
</head>

<body>
<?php
// body head
include('head.php');
?>
<!-- content start -->
	<div id="content">
		<div class="post">
			<h2 class="title">My Site</h2>
			<p class="byline"><small>small site</small></p>
			<div class="entry">
				<p>sss</p>
			</div>
			<p class="links">
				<a href="#" class="more">Link1</a>
				&nbsp;&nbsp;&nbsp;
				<a href="#" class="comments">Link2</a>
				(
				<a href="#" class="rss">Link3</a>
				)
			</p>
		</div>
	</div>
<!-- content ends -->
<?php
// body foot
include('foot.php');
?>
</body>
</html>
<?php
// after html coding
include('end.php');
?>