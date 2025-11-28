<?php

include_once("../php/ez_sql.php");

class userAdmin
{

	function addUser($data)
	{
		$data["password"] = md5($data["password"]);
		
		$columns = array_keys($data);
		$values = array_values($data);
		foreach ($columns as $column)
		{
			$arr_column[] = "`" . $column . "`";
		}
		foreach ($values as $value)
		{
			if ($value != "1" && $value != "0")
			{
				$arr_value[] = "'" . $value . "'";
			}
				else
				{
					$arr_value[] = $value;
				}
		}
		$comma_separated_columns = implode(",", $arr_column);
		$comma_separated_values = implode(",", $arr_value);
		
		global $db;
		$query = "INSERT INTO users ($comma_separated_columns) VALUES ($comma_separated_values)";
		//print $query;
		$db->query($query);
	}
	
	function updateUser($data)
	{
		$email = $data["email"];
		
		foreach ($data as $key=>$value)
		{
			if ($key != "password")
			{
				if ($value != "1" && $value != "0")
				{
					$array[] = "users." . $key . " = " . "'" . $value . "'";
				}
					else
					{
						$array[] = "users." . $key . " = " . $value;
					}
			}
				elseif ($key == "password" && !empty($value))
				{
					$array[] = "users." . $key . " = " . "'" . md5($value) . "'";
				}
		}
		$comma_seperated_pairs = implode(", ", $array);
	
		global $db;
		$query = "UPDATE users SET $comma_seperated_pairs WHERE users.email = '$email'";
		//print $query;
		$db->query($query);
	}
	
	function userCount()
	{
		$query = "SELECT COUNT(email) AS total FROM users";
		global $db;
		$total = $db->get_var($query);
		return ($total);
	}
	
	function getUser($email, $column, $order, $entries, $pageindex, $year, $month, $day, $year_end, $month_end, $day_end, $count)
	{
		if (empty($column))
		{
			$column = "date";
		}
		
		if (empty($order))
		{
			$order = "DESC";
		}
		
		if (empty($entries))
		{
			$entries = "10";
		}
		
		if (empty($pageindex))
		{
			$pageindex = "0";
		}
		
		if (empty($year))
		{
			$year = '0000';
		}
		
		if (empty($month))
		{
			$month = '00';
		}
		
		if (empty($day))
		{
			$day = '00';
		}
		
		if (empty($year_end))
		{
			$year_end = '0000';
		}
		
		if (empty($month_end))
		{
			$month_end = '00';
		}
		
		if (empty($day_end))
		{
			$day_end = '00';
		}
		
		$from = $year . $month . $day;
		
		$to = $year_end . $month_end . $day_end;
		
		if ($from != '00000000' && $to != '00000000')
		{
			$range = "AND date BETWEEN $from and $to";
		}
			elseif ($from != '00000000' && $to == '00000000')
			{
				if ($year != '0000')
				{
					$i = "AND YEAR(date) = $year";
				}
					if ($month != '00')
					{
						$j = " AND MONTH(date) = $month";
					}
						if ($day != '00')
						{
							$k = " AND DAYOFMONTH(date) = $day";
						}
				$specific = $i . $j . $k;
			}
		
		//$query = "SELECT * FROM users WHERE users.email = '$email'";
		if ($count == true)
		{
				$query = "SELECT COUNT(email) AS total FROM users WHERE POSITION('$email' IN users.email) $specific $range ORDER BY $column $order LIMIT $pageindex, $entries";
				//print $query;
				global $db;
				$i = $db->get_var($query);
				return ($i);
		}
			elseif ($count == false)
			{
				$query = "SELECT user_id,DATE_FORMAT(users.date, '%Y-%m-%d') as date,email,password,first_name,last_name,e_updates,region,notes,status FROM users WHERE POSITION('$email' IN users.email) $specific $range ORDER BY $column $order LIMIT $pageindex, $entries";
				//print $query;
				global $db;
				$user = $db->get_results($query);
				return ($user);
			}
		
		
	}

}

//$admin_user = new userAdmin();
//$admin_user->addUser("hello");




?>