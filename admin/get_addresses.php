<? 

include_once("../php/ez_sql.php");

global $db;

$region = $_POST['region'];

if (!empty($region)) 
{
	if ($region != "all")
	{
		$query = "SELECT email_address FROM newsletter_mailing_list WHERE region = '$region'";
	}
		else
		{
			$query = "SELECT email_address FROM newsletter_mailing_list";
		}
	$addresses = $db->get_results($query);
	//print_r($addresses);
	if (!empty($addresses))
	{
		foreach ($addresses as $address)
		{
			$string .= $address->email_address . ",";
		}
		$string = rtrim($string, ",");
	}
		else
		{
			$string = "There are no email addresses for the " . $region . " region.";
		}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Get newsletter addresses</title>
	<meta name="generator" content="BBEdit 8.2" />
</head>
<body>

<form name="choose_region" id="choose_region" method="post" style="text-align: left;">

<select name="region" onchange="this.form.submit();">
	<option value="" selected="selected">Select a region</option>
	<option value="all">All Regions</option>
	<option value="hokkaido">Hokkaido</option>
	<option value="tohoku">Tohoku</option>
	<option value="kanto">Kanto</option>
	<option value="chubu">Chubu</option>
	<option value="kinki">Kinki</option>
	<option value="chugoku">Chugoku</option>
	<option value="shikoku">Shikoku</option>
	<option value="kyushu">Kyushu/Okinawa</option>
	<option value="outside">Outside Japan</option>
</select>

</form>

<textarea name="message" id="message" style="width: 500px; height: 600px;"><? print $string; ?></textarea>

</body>
</html>
