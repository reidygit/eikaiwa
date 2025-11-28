<?php

define("HTML_DIR", "../html/");

include_once("ez_sql.php");
include_once("email_class.php");

global $db;
$email = $_POST['email'];

if (isset($_GET['confirm']))
{
	# process the email address
	if ($test = $db->get_var("SELECT user_id FROM users WHERE email = '$email'") && $db->get_var("SELECT e_updates FROM users WHERE email = '$email'") == 1)
	{
		$query = "UPDATE users SET e_updates = 0 WHERE email = '$email'";
		$db->query($query);
		//include_once("e_updates_unsubscribe_comf.html");
		$new_email = new emailClass();
		$new_email->e_updates_unsubscribe($email);
		include_once(HTML_DIR . "e_updates_unsubscribe_comf.html");
	}
		else
		{
			# display form with error message
			$error = "The supplied e-mail address is not currently on our mailing list.  Please check the address and try again.";
			include_once(HTML_DIR . "e_updates_unsubscribe.html");
		}
}
	else
	{
		# default case when the page loads
		include_once(HTML_DIR . "e_updates_unsubscribe.html");
	}

?>