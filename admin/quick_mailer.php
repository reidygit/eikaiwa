<?php

//print_r($_POST);
//print_r($_FILES);

function encode_attachment($file)
{
	$file_tmp  = $file['attachment']['tmp_name'];
	$file_type = $file['attachment']['type']; 
	$file_name = $file['attachment']['name'];
	
	if (is_uploaded_file($file_tmp)) 
	{ 
		// Read the file to be attached ('rb' = read binary) 
		$file = fopen($file_tmp,'rb'); 
		$data = fread($file,filesize($file_tmp)); 
		fclose($file);
	}
	
	$data = chunk_split(base64_encode($data));
	
	return ($data);
}

function send_mail($mail, $attachment)
{
	$to        = $mail['to'];
	$from      = $mail['from'];
	$subject   = $mail['subject'];
	$body 	   = $mail['body'];
	
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
		$content_type = "multipart/mixed\n";
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
	
	//print $headers;
	//print $message;
	mail($to, $subject, $message, $headers);
}

if (!empty($_POST["to"]) && !empty($_POST["subject"]) && !empty($_POST["body"]))
{
	send_mail($_POST, $_FILES);
	print "your mail is on its way<br/>";
	?> <a href="#" onclick="javascript: window.close();">close</a><?
}
	else
	{
		include_once("quick_mailer.html");
	}

?>