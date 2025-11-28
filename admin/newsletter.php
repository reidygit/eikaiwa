<?php

include_once("../php/ez_sql.php");

$region = $_POST['region'];
$subject = $_POST['subject'];
$body = $_POST['body'];

function archiveNewsletter($region, $subject, $body)
{
	# save the body in the db
	global $db;

	$query = "INSERT INTO e_updates (region,subject,body) VALUES ('$region','$subject','$body')";
	$db->query($query);
}

function getAddress($region)
{
	# get the addresses and format
	global $db;
	
	$query = "SELECT users.email
			  FROM users
			  WHERE users.e_updates = 1 AND
			  users.region = '$region'";
	$addresses = $db->get_results($query);
	
	return($addresses);
}

function send_mail($to, $subject, $body, $attachment)
{

	$from      = "nihongo.fm! <subscriptions@nihongo.fm>";
	
	$file_tmp  = $attachment['attachment']['tmp_name'];
	$file_type = $attachment['attachment']['type']; 
	$file_name = $attachment['attachment']['name'];
	
	if (is_uploaded_file($file_tmp)) 
	{ 
		// Read the file to be attached ('rb' = read binary) 
		$file = fopen($file_tmp,'rb'); 
		$data = fread($file,filesize($file_tmp)); 
		fclose($file);
	}
	
	$data = chunk_split(base64_encode($data));
	
	if (!empty($data))
	{
		$semi_rand = md5(time()); 
		$mime_boundary = "Panda-Mail--{$semi_rand}";
		$content_type = "multipart/mixed; \n";
		$boundry = " boundary=\"{$mime_boundary}\"\n\n";
	}
		else
		{
			$content_type = "text/html\n\n";
		}
	
	$headers .= "From: " . $from . "\n";
	$headers .= "X-Mailer: Panda Mail - PHP/" . phpversion() . "\n";
	$headers .= "MIME-Version: 1.0\nContent-Type: " . $content_type;
    $headers .= $boundry;
	
	if (!empty($data))
	{
		$message .= "--{$mime_boundary}\n" . 
           			"Content-Type: text/html; charset=\"iso-8859-1;\"\n" . 
           			"Content-Transfer-Encoding: 7bit\n\n" . 
           			$body . "\n\n";
		$message .= "--{$mime_boundary}\n" . 
					"Content-Transfer-Encoding: base64\n" . 
            		"Content-Type:" . $file_type . ";\n" . 
            		"\tname=\"" . $file_name . "\"\n" . 
            		"Content-Disposition: attachment;\n" . 
            		"\tfilename=\"" . $file_name . "\"\n\n" . 
            		$data . "\n\n" . 
            		"--{$mime_boundary}--\n"; 
	}
		else
		{
			$message = $body;
		}
	//print $to;
	//print $subject;
	//print $headers;
	//print $message;
	mail($to, $subject, $message, $headers);
}

if (!empty($_POST['region']) && !empty($_POST['subject']) && !empty($_POST['body']))
{
	archiveNewsletter($region, $subject, $body);
	$address_list = getAddress($region);
	//print_r($address_list);
	
	foreach ($address_list as $address)
	{
		send_mail($address->email, $subject, $body, $_FILES);
		//$mail->e_updates($address->email, $subject, $body);
	}
	print "Your newsletter has been archived and sent";
}

include_once("./newsletter.html");
?>