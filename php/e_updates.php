<?php
include_once("ez_sql.php");
include_once("email_class.php");

global $db;

$email = $_GET['updates_email'];
$region = $_GET['region'];

if (isset($_GET['updates_email']))
{
	if ($test = $db->get_var("SELECT user_id FROM users WHERE email = '$email'"))
	{
		if ($db->get_var("SELECT e_updates FROM users WHERE email = '$email'") == 1)
		{
			# registered user who as already signed up for e-updates
			$error_newsletter = "The supplied email address has already been signed up for e-updates";
		}
			else
			{
				# registered user who has not yet set their e-updates prefs
				$query = "UPDATE users SET e_updates = 1, region = '$region' WHERE email = '$email'";
				$db->query($query);
				$new_email = new emailClass();
				$new_email->e_updates_subscribe($email);
			}
		
	}
		else
		{
			# new user signing up for e-updates
			$query = "INSERT INTO users(email, e_updates, region) VALUES('$email', 1, '$region')";
			$db->query($query);
			$new_email = new emailClass();
			$new_email->e_updates_subscribe($email);
		}

}
?>