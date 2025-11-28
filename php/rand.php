<?php

class passwordClass
{

	var $alpha = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	var $numbers = array("0","1","2","3","4","5","6","7","8","9");

	function create_password()
	{
		shuffle($this->alpha);
		shuffle($this->numbers);
		
		for ($i = 0; $i < 6; $i++)
		{
			$a_password[]= array_pop($this->alpha);
		}
		
		for ($i = 0; $i < 6; $i++)
		{
			$n_password[]= array_pop($this->numbers);
		}
		
		$merged = array_merge($a_password, $n_password);
		
		shuffle($merged);
		$password = implode("", $merged);
		return($password);	
	}
}

//$password = new passwordClass();
//$password->create_password();

?>