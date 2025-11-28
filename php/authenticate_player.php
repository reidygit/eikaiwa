<?php

function display_login($error, $stream, $output, $playlist)
	{
		include_once("../html/player_login.html");
	}
	
	function display_forgot_password($error)
	{
		include_once("../html/forgotten_password.html");
	}
	
	function display_conformation($email)
	{
		include_once("../html/forgotten_password_email.html");
	}
	
	// below is code to secure a web page
	// this code will be included at the top of any page you wish to secure
	// this code will not go on the home page
	
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
			include_once("../html/player_rc1.html");
			exit;
		}
			else
			{
				//echo "bad cookie, bad cookie!!!";
				display_login($error, $stream, $output, $playlist);
				exit;
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
				$error = true;
				display_login($error, $stream, $output, $playlist);
				exit;
			}
				else
				{
					$cookies = new cookieClass();
					$cookies->email = $email;
					if (isset($_POST["forever"]))
					{
						$cookies->expiration = 3600 * 24 * 365;
					}
					$cookies->set_cookie();
					//$name = $auth->get_name($email);
					//$_SESSION["name"] = $name;
					$reload = "onload=\"setTimeout('opener.location.reload()',1000);\"";
					include_once("../html/player_rc1.html");
					exit;
				}
			
		}
			else
			{
				//echo "if the user does not have a cookie and has not supplied credentials then show the login box";
				if (isset($_GET['forgot']))
				{
					display_forgot_password($error);
					exit;
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
							exit;
						}
							else
							{
								$error = true;
								display_forgot_password($error);
								exit;
							}
					}
						else
						{
							display_login($error, $stream, $output, $playlist);
							exit;
						}
			}
			include_once("../html/player_rc1.html");
			exit;
?>