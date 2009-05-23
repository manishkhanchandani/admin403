<?php
$to = "juhikh@yahoo.com";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";
$hdrs = "From: asimonson@verityinvest.com \r\n".
	"Reply-To: asimonson@verityinvest.com \r\n".
	"X-Mailer: PHP/". phpversion() 
	
	  ;
if (mail($to, $subject, $body, $from)) {
  echo("<p>Message successfully sent!</p>");
 } else {
  echo("<p>Message delivery failed...</p>");
 }
?>

