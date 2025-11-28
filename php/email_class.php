<?php

include_once("ez_sql.php");

class emailClass
{
	var $from;
	var $cc;
	var $return_path;
	
	function send_mail($to, $subject, $message)
	{
		mail($to, trim($subject), wordwrap($message, 70), $this->headers(), $this->return_path);
	}
	
	function headers()
	{
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "From: " . $this->from . "\n";
		if (!empty($this->cc))
		{
			$headers .= "Cc: " . $this->cc . "\n";
		}
		$headers .= "Return-Path: " . $this->from . "\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		return ($headers);
	}
	
	function get_mail($id)
	{
		global $db;
		$query = "SELECT email_predefined.subject, 
			  email_predefined.body
			  FROM email_predefined
			  WHERE email_predefined.id = $id";
		$mail = $db->get_row($query);
		return($mail);
	}
	
	function subscription_new($to, $password)
	{
		$this->from = "eikaiwa.fm! <subscriptions@eikaiwa.fm>";
		$this->cc = "";
		$this->return_path = '-f "subscriptions@eikaiwa.fm"';
		$mail = $this->get_mail(1);
		$body = html_entity_decode($mail->body);
		$search = array("[TO]", "[PASSWORD]");
		$replace = array($to, $password);
		
		$subject = $mail->subject;
		$message = str_replace($search, $replace, $body);

		$this->send_mail($to, $subject, $message);
	}
	
	function subscription_cancellation($to)
	{
		$this->from = "eikaiwa.fm! <subscriptions@eikaiwa.fm>";
		$this->cc = "";
		$this->return_path = '-f "subscriptions@eikaiwa.fm"';
		$mail = $this->get_mail(2);
		$subject = $mail->subject;
		$message = html_entity_decode($mail->body);
		
		$this->send_mail($to, $subject, $message);
	}
	
	function subscription_renewal($to)
	{
		$this->from = "eikaiwa.fm! <subscriptions@eikaiwa.fm>";
		$this->cc = "";
		$this->return_path = '-f "subscriptions@eikaiwa.fm"';
		$mail = $this->get_mail(3);
		
		$subject = $mail->subject;
		$message = html_entity_decode($mail->body);
		
		$this->send_mail($to, $subject, $message);
	}
	
	function e_updates_subscribe($to)
	{
		$this->from = "eikaiwa.fm! <updates@eikaiwa.fm>";
		$this->cc = "";
		$this->return_path = '-f "updates@eikaiwa.fm"';
		$mail = $this->get_mail(4);
		
		$subject = $mail->subject;
		$message = html_entity_decode($mail->body);
		
		$this->send_mail($to, $subject, $message);
	}
	
	function e_updates_unsubscribe($to)
	{
		$this->from = "eikaiwa.fm! <updates@eikaiwa.fm>";
		$this->cc = "";
		$this->return_path = '-f "updates@eikaiwa.fm"';
		$mail = $this->get_mail(5);
		
		$subject = $mail->subject;
		$message = html_entity_decode($mail->body);
		
		$this->send_mail($to, $subject, $message);
	}
	
	function e_updates($to, $subject, $message)
	{
		$this->from = "eikaiwa.fm! <updates@eikaiwa.fm>";
		$this->cc = "";
		$this->return_path = '-f "updates@eikaiwa.fm"';
		
		$this->send_mail($to, $subject, $message);
	}
	
	function forgotten_password($to, $password)
	{
		$this->from = "eikaiwa.fm! <subscriptions@eikaiwa.fm>";
		$this->cc = "";
		$this->return_path = '-f "subscriptions@eikaiwa.fm"';
		$mail = $this->get_mail(6);
		$body = html_entity_decode($mail->body);
		$search = array("[TO]", "[PASSWORD]");
		$replace = array($to, $password);
		
		$subject = $mail->subject;
		$message = str_replace($search, $replace, $body);

		$this->send_mail($to, $subject, $message);
	}
	
	function quick_mail($to, $subject, $message)
	{
		$this->from = "eikaiwa.fm! <subscriptions@eikaiwa.fm>";
		$this->cc = "";
		$this->return_path = '-f "subscriptions@eikaiwa.fm"';
		
		$this->send_mail($to, $subject, $message);
	}
	
}

//$email = new emailClass();
//$email->subscription_new("icabbit@mac.com", "helloworld");

?>