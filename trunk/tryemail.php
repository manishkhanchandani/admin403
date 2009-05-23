<?php
include("includes/pear/Mail.php");
/* mail setup recipients, subject etc */
$recipients = "juhikh@yahoo.com";
$headers["From"] = "asimonson@verityinvest.com";
$headers["To"] = "juhikh@yahoo.com";
$headers["Reply-To"] = "asimonson@verityinvest.com";
$headers["Subject"] = "User feedback";
$mailmsg = "Hello, This is a test.";
/* SMTP server name, port, user/passwd */
$smtpinfo["host"] = "smtp.comcast.net";
$smtpinfo["port"] = "25";
$smtpinfo["auth"] = true;
$smtpinfo["username"] = "juhikhan@comcast.net";
$smtpinfo["password"] = "Sports77";
/* Create the mail object using the Mail::factory method */
$mail_object =& Mail::factory("smtp", $smtpinfo);
/* Ok send mail */
if ($mail_object->send($recipients, $headers, $mailmsg))
{
  echo("<p>Message successfully sent!</p>");
}
else
{
  echo("<p>Message delivery failed...</p>");
}


?>

