<?php
include_once("ez_sql.php");
include_once("email_class.php");

class cookieClass
{
	
	// cookie data
	var $email;
	
	// cookie structure
	var $cookiename = "eikaiwafm";
	var $expiration = 3600;
	var $version = 0.99;
	var $glue = ",";

	// cypher info
	var $cypher = "blowfish";
	var $mode = "ecb";
	var $key = "pandas of course";
	
	function set_cookie()
	{
		// sets the cookie, duh!
		setcookie($this->cookiename, $this->bake_cookie(), time() + $this->expiration, "/");
	}
	
	function bake_cookie()
	{
		// assembles the cookie data
		$cookie_dough = $this->email . $this->glue . $this->version;
		$baked = $this->encrypt_cookie($cookie_dough);
		return ($baked);
	}
	
	function varify_cookie($encrypted_cookie)
	{
		// checks whether the info in a cookie is good and valid
		// queries the db to make sure the email address is registered
		// checks that the cookie version number is correct
		// if the above criteria are met, return true
		
		$decrypted_cookie = $this->decrypt_cookie($encrypted_cookie);
		$cookie_data = explode(",",$decrypted_cookie);
		
		global $db;
		$query = "SELECT email FROM users";
		$users = $db->get_results($query);
		foreach ($users as $usr)
		{
			if ($cookie_data[0] == $usr->email && $cookie_data[1] == $this->version)
			{
				return(true);
			}
		}
	}
	
	function encrypt_cookie($raw_cookie)
	{
 		// encrypt cookie data
 		$td = mcrypt_module_open($this->cypher,'',$this->mode,'');
		$key = substr($this->key, 0, mcrypt_enc_get_key_size($td));
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $this->key, $iv);
		$encrypted = mcrypt_generic($td, $raw_cookie);
		mcrypt_generic_deinit($td);
 		return($encrypted);
	}
	
	function decrypt_cookie($encrypted_cookie)
	{
		// decrypt cookie data
		$td = mcrypt_module_open($this->cypher,'',$this->mode,'');
		$key = substr($this->key, 0, mcrypt_enc_get_key_size($td));
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $this->key, $iv);
		$decrypted = mdecrypt_generic($td, $encrypted_cookie);
		mcrypt_generic_deinit($td);
		return($decrypted);
	}
	
	function logout()
	{
		setcookie($this->cookiename, "", time()-3600);
		//setcookie("nihongofm", "", time()-3600);
	}

}

class authenticate
{

	function check_credentials($email, $password)
	{
		//echo md5($password);
		
		global $db;
		$query = "SELECT password, status FROM users WHERE email = '$email'";
		$info = $db->get_row($query);
		
		if ($info->password == md5($password) && $info->status == "current")
		{
			return(true);
		}
		/*
		$query = "SELECT email, password FROM users";
		$users = $db->get_results($query);
		foreach ($users as $usr)
		{
			if ($email == $usr->email && $password == $usr->password)
			{
				return(true);
			}
		}
		*/
	}
	
	function registered_email($email)
	{
		global $db;
		$query = "SELECT email FROM users";
		$data = $db->get_results($query);
		foreach ($data as $em)
		{
			if ($email == $em->email)
			{
				return(true);
			}
		}
	}
	
	function get_name($email)
	{
		global $db;
		$query = "SELECT last_name FROM users WHERE email = '$email'";
		$name = $db->get_row($query) ;
		return($name);
	}
	
	function get_password($email)
	{
		global $db;
		$query = "SELECT password FROM users WHERE email = '$email'";
		$password = $db->get_row($query) ;
		return($password->password);
	}
}
?>