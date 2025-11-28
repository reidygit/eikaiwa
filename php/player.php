<?php

$browser = $_SERVER["HTTP_USER_AGENT"];
if (strpos($browser, "Safari"))
{
	$iframe_height = 115;
}
	elseif (strpos($browser, "Gecko") && !strpos($browser, "Safari") or strpos($browser, "Firefox") or strpos($browser, "Netscape"))
	{
		if (strpos($browser, "Macintosh"))
		{
			$iframe_height = 115;
		}
		if (strpos($browser, "Windows"))
		{
			$iframe_height = 120;
		} 
	}
		else
		{
			// Windows IE
			$iframe_height = 120;
		}

$stream = $_GET['channel'];

if ($stream == "eikaiwa_free")
{
	$title = "FREE eikaiwa.fm Station";
	$preroll = "http://www.eikaiwa.fm/prerolls/eikaiwa_free_preroll.mov";
	$movie = "http://www.eikaiwa.fm/prerolls/eikaiwa_free.mov";
	$movie_info = "http://68.178.149.70/current_track.php?playlist=eikaiwafreestream&output=player";
	$current_track = "http://68.178.149.70/current_track.php?playlist=eikaiwafreestream&output=player";
	$track_history = "http://68.178.149.70/current_track.php?playlist=eikaiwafreestream&output=track_list";
	include_once("../html/player_rc1.html");
}
	elseif ($stream == "eikaiwa_premium")
	{
		$title = "PREMIUM eikaiwa.fm  Station";
		$preroll = "http://www.eikaiwa.fm/prerolls/eikaiwa_premium_preroll.mov";
		$movie = "http://www.eikaiwa.fm/prerolls/eikaiwa_premium.mov";
		$movie_info = "http://68.178.149.70/current_track.php?playlist=eikaiwapremiumstream&output=player";
		$current_track = "http://68.178.149.70/current_track.php?playlist=eikaiwapremiumstream&output=player";
		$track_history = "http://68.178.149.70/current_track.php?playlist=eikaiwapremiumstream&output=track_list";
		include_once("authenticate_class.php");
		include_once("authenticate_player.php");
	}
		elseif (isset($_GET["forgot"]) || isset($_GET["confirm"]))
		{
			include_once("forgotten_password.php");
		}

?>