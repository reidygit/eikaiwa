<?php

include_once("authenticate_class.php");

if (isset($_GET['logout']))
{
	//print "logout";
	$cookie = new cookieClass();
	$cookie->logout();
	header("Location: index.php");
}

if (isset($_COOKIE["eikaiwafm"]))
{
	//echo "if a cookie is set log the user in with a session";
	$cookie = $_COOKIE["eikaiwafm"];
	$new_cookie = new cookieClass();
	if ($new_cookie->varify_cookie($cookie) != false)
	{
		//get name and set to session var
		//$name = $cookie->get_cookie_info();
		//$_SESSION["name"] = $name;
		$display_login = false;
		$display_greeting = true;
	}
		else
		{
			//echo "bad cookie, bad cookie!!!";
			$display_login = true;
		}	
}
	elseif (isset($_POST["email"]) && isset($_POST["password"]))
	{
		//echo "if no cookie is set but the user is logging in check the users credentials";
		$email = $_POST["email"];
		$password = $_POST["password"];
		$auth = new authenticate();
		
		if ($auth->check_credentials($email, $password) == false)
		{
			//echo "if the users credentials don't work display the login window";
			$error_login = "please varify your email and password";
			$display_login = true;
		}
			else
			{
				//echo "upon sucsessful credential checking log the user in, give the user a cookie, and session";
				$cookies = new cookieClass();
				$cookies->email = $email;
				if (isset($_POST["forever"]))
				{
					$cookies->expiration = 3600 * 24 * 365;
				}
				$cookies->set_cookie();
				//$name = $auth->get_name($email);
				//$_SESSION["name"] = $name;
				$display_greeting = true;
			}
		
	}
		else
		{
			//echo "if the user does not have a cookie and has not supplied credentials then show the login box";
			if (isset($_GET['forgot']))
			{
				display_forgot_password($error);
				break;
			}
				elseif(isset($_GET['confirm']))
				{
					$auth = new authenticate();
					$new_email = new emailClass();
					if($auth->registered_email($_POST['email']) == true)
					{
						$password = $auth->get_password($_POST['email']);
						$new_email->lost_password($_POST['email'], $password);
						display_conformation($_POST['email']);
						break;
					}
						else
						{
							$error = true;
							display_forgot_password($error);
							break;
						}
				}
					else
					{
						$display_login = true;
					}
		}

?>