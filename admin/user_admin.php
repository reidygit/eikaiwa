<?php

//print_r($_POST);
//print_r($_GET);

include_once("user_admin_class.php");
include_once("../php/pagination_class.php");

$admin_user = new userAdmin();
$page = new pagination();

// edit a user case
if (!empty($_POST["email"]))
{
	$email = $_POST["email"];
}
	else
	{
		$email = $_GET["email"];
	}
if (!empty($_POST["year"]))
{
	$year = $_POST["year"];
}
	else
	{
		$year = $_GET["year"];
	}
if (!empty($_POST["month"]))
{
	$month = $_POST["month"];
}
	else
	{
		$month = $_GET["month"];
	}
if (!empty($_POST["day"]))
{
	$day = $_POST["day"];
}
	else
	{
		$day = $_GET["day"];
	}
if (!empty($_POST["year_end"]))
{
	$year_end = $_POST["year_end"];
}
	else
	{
		$year_end = $_GET["year_end"];
	}
if (!empty($_POST["month_end"]))
{
	$month_end = $_POST["month_end"];
}
	else
	{
		$month_end = $_GET["month_end"];
	}
if (!empty($_POST["day_end"]))
{
	$day_end = $_POST["day_end"];
}
	else
	{
		$day_end = $_GET["day_end"];
	}
	
$entries = $_GET["entries"];
$pageindex = $_GET["pageindex"];
$column = $_GET["column"];
$order = $_GET["order"];

if (isset($_GET["newuser"]))
{
	// new user case
	include_once("header.html");
	include_once("user_admin.html");
	include_once("footer.html");
}

if (isset($_GET["finduser"]))
{
	//user lookup case
	include_once("header.html");
	include_once("user_admin_search.html");
	include_once("footer.html");
}

if (isset($_GET["edituser"]))
{
	
	$total = $admin_user->getUser($email, $column, $order, $entries, $pageindex, $year, $month, $day, $year_end, $month_end, $day_end, true);
	
	$user = $admin_user->getUser($email, $column, $order, $entries, $pageindex, $year, $month, $day, $year_end, $month_end, $day_end, false);
	
	if (count($user) >= 2)
	{
		include_once("header.html");
		include_once("user_admin_search.html");
		include_once("user_list_short.html");
		include_once("footer.html");
	}
		elseif (count($user) == 1)
		{
			include_once("header.html");
			include_once("user_admin_search.html");
			include_once("user_admin.html");
			include_once("footer.html");
		}
			else
			{
				print "sorry there are no users matching your search, please try again";
			}
}

if (isset($_GET["transaction_list"]))
{
	include_once("header.html");
	include_once("transactions.php");
	include_once("footer.html");
}

if (isset($_GET["commitnew"]))
{
	// enter a new user into the db
	print "processing new user";
	$admin_user->addUser($_POST);
}

if (isset($_GET["commitupdate"]))
{
	// update an existing users db info
	print "updating user record";
	$admin_user->updateUser($_POST);
}
?>