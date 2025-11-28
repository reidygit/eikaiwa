<?php

//print_r($_POST);

include_once("ez_sql.php");
include_once("authenticate_class.php");

$check_cookie = new cookieClass();
$cookie = $_COOKIE["eikaiwafm"];
$new_cookie = new cookieClass();
if ($new_cookie->varify_cookie($cookie) != false)
{
	if (isset($_POST["old_pwd"]) && !empty($_POST["old_pwd"]) && isset($_POST["new_pwd"]) && !empty($_POST["new_pwd"]) && isset($_POST["check"]) && !empty($_POST["check"]))
	{
		
		$old_pwd = md5($_POST["old_pwd"]);
		$new_pwd = $_POST["new_pwd"];
		$check = $_POST["check"];
		
		global $db;
		
		if ($check_cookie->varify_cookie($cookie) != false)
		{
			$decrypted_cookie = $check_cookie->decrypt_cookie($cookie);
			$cookie_data = explode(",",$decrypted_cookie);
			
			$query = "SELECT password FROM users WHERE email = '$cookie_data[0]'";
			
		}
		
		//$query = "SELECT password FROM users WHERE password = '$old_pwd'";
		if ($old_pwd == $db->get_var($query))
		{
			if (strlen($new_pwd) >= 7)
			{
				if ($new_pwd == $check)
				{
					// process request and show sucsessfully changed screen
					print "you have sucsessfully changed your password";
					$password = md5($new_pwd);
					$update = "UPDATE users SET password = '$password' WHERE email = '$cookie_data[0]'";
					$db->query($update);
				}
					else
					{
						$message = "the new passwords you enter do not match.  please re-enter your new password";
						print $message;
						include_once("../html/chpwd.html");
					}
			}
				else
				{
					$message = "the new password you entered is not long enough.  please make sure your new password is 7 charachters long";
					print $message;
					include_once("../html/chpwd.html");
				}
		}
			else
			{
				$message = "the password you enter is not in our records, please check the password and try again";
				print $message;
				include_once("../html/chpwd.html");
			}
	}
		else
		{
			include_once("../html/chpwd.html");
		}
}
	else
	{
		print "bad panda!!! go eat bamboo somewhere else, you've been warned . . .";
	}


?>