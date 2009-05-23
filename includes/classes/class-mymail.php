<?php
include('Mail.php');
include('Mail/mime.php');

class mymail {
	public $to;
	public $headers;
	public $cc;
	public $bcc;
	public $subject;
	public $message;
	public $attachment;
	public $txt;
	public $html;
	public $from;
	public $replyto;
	
	public function emailSimple() {
		if(@mail($this->to, $this->subject, $this->message, $this->headers)) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function emailHTML() {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= $this->headers;
		if(@mail($this->to, $this->subject, $this->message, $headers)) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function emailAttachment() {
	        $mime = new Mail_mime();
	        $mime->setTXTBody($this->txt);
	        $mime->setHTMLBody($this->html);
	        if($this->attachment) {
	            foreach($this->attachment as $file=>$type) {
	                $mime->addAttachment($file, $type);
	            }
	        }
	        $body = $mime->get();
	        $headers = $mime->headers(array("From"=>$this->from, "Subject"=>$this->subject, "Reply-To"=>$this->from));
	        /* SMTP server name, port, user/passwd */
	        $smtpinfo["host"] = "smtp.comcast.net";
	        $smtpinfo["port"] = "25";
	        $smtpinfo["auth"] = true;
	        $smtpinfo["username"] = "juhikhan@comcast.net";
	        $smtpinfo["password"] = "Sports77";
	        /* smtp ends */
	        $mail =& Mail::factory("smtp", $smtpinfo);
	        if(!$mail->send($this->to,$headers,$body)) {
	            return 0;
	        } else {
	            return 1;
	        }
	}

	
	public function emailTxtHtml() {
		$mime = new Mail_mime();
		$mime->setTXTBody($this->txt);
		$mime->setHTMLBody($this->html);
		$body = $mime->get();
		$headers = $mime->headers(array("From"=>$this->from, "Subject"=>$this->subject));
		$mail =& Mail::factory("mail");
		if(!$mail->send($this->to,$headers,$body)) {
			return 0;
		} else {
			return 1;
		}
	}
}
?>
