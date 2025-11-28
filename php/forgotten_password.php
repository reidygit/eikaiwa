<?php
include_once("authenticate_class.php");
include_once("email_class.php");
include_once("rand.php");
include_once("ez_sql.php");

function display_forgot_password($error)
{
	include_once("../html/forgotten_password.html");
}

function display_conformation($email)
{
	include_once("../html/forgotten_password_email.html");
}

if (isset($_GET['forgot']))
{
	display_forgot_password($error);
	//break;
}
	elseif(isset($_GET['confirm']))
	{
		$email = $_POST['email'];
		$auth = new authenticate();
		$new_email = new emailClass();
		if($auth->registered_email($_POST['email']) == true)
		{

			$pass = new passwordClass();
			$password = $pass->create_password();
			
			$hash = md5($password);
			
			global $db;
			$query = "UPDATE users SET password = '$hash' WHERE email = '$email'";
			if ($db->query($query))
			{
				//$password = $auth->get_password($_POST['email']);
				//$new_email->lost_password($_POST['email'], $password);
				$new_email->forgotten_password($email, $password);
				display_conformation($email);
				//break;
			}
		}
			else
			{
				$error = true;
				display_forgot_password($error);
				//break;
			}
	}

?>