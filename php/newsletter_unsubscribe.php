<?
include_once("ez_sql.php");

global $db;

$email_address = $_POST['unsubscribe_email_address'];
$action = $_GET['action'];

if (!empty($email_address))
{
	$prevlocation = rawurlencode("http://www.eikaiwa.fm/html/newsletter_unsubscribe_confirmation.html");
	$query = "UPDATE  newsletter_mailing_list SET status = 0 WHERE email_address = '$email_address'";
	if ($db->query($query))
	{
		header("Location: http://www.eikaiwa.fm/cgi-bin/newsletter.pl?type=cancel&email=$email_address&prevlocation=$prevlocation");
		//include_once("../html/newsletter_unsubscribe_confirmation.html");	
	}
		else
		{
			$response_message = "The email address you supplied is not currently registered.  Please check the address and try again.  If you feel this is an error please contact us directly.";
			include_once("../html/newsletter_unsubscribe.html");
		}

}
	else
	{
		include_once("../html/newsletter_unsubscribe.html");
	}
?>