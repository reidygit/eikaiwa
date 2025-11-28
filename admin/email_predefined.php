<?php
//print_r($_POST);

include_once("../php/ez_sql.php");

$mail_id = $_POST['mail_id'];
$subject = $_POST['subject'];
$body = $_POST['body'];

function getMail($mail_id)
{
	global $db;
	$query = "SELECT email_predefined.subject, 
			  email_predefined.body
			  FROM email_predefined
			  WHERE email_predefined.id = $mail_id";
	$mail = $db->get_row($query);
	return($mail);
}

function saveMail($mail_id, $subject, $body)
{
	print "sucsessfully saved";
	global $db;
	$formatted = htmlspecialchars($body);
	$query = "UPDATE email_predefined 
			  SET email_predefined.subject = '$subject',
			  email_predefined.body = '$formatted'
			  WHERE email_predefined.id = $mail_id";
	$db->query($query);
}

if (!isset($_POST['action']) && !empty($_POST))
{
	$mail = getMail($mail_id);
	//print_r($mail);
}
	elseif (isset($_POST['action']))
	{
		saveMail($mail_id, $subject, $body);
	}


include_once("email_predefined.html");



?>