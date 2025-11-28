<?php

//print_r($_COOKIE);

define("HTML_DIR", "./html/");
define("IMG_DIR", "./img/");
define("CSS_DIR", "./css/");
define("JS_DIR", "./javascript/");
define("PHP_DIR", "./php/");
define("INDEX_FILE", "index2.php");
define("PODCAST_LINK", "podcast.xml");
define("STORE_URL", "http://store.eikaiwa.fm/store.php");
define("FREE_PLAYER_URL", "http://www.eikaiwa.fm/php/player.php?channel=eikaiwa_free");
define("PREMIUM_PLAYER_URL", "http://www.eikaiwa.fm/php/player.php?channel=eikaiwa_premium");
define("FREE_CURRENT_TRACK", "http://68.178.149.70/current_track.php&#063;playlist=eikaiwafreestream&amp;output=index");
define("PREMIUM_CURRENT_TRACK", "http://68.178.149.70/current_track.php&#063;playlist=eikaiwapremiumstream&amp;output=index");

include_once(PHP_DIR . "authenticate.php");
include_once(PHP_DIR . "e_updates.php");

$browser = $_SERVER["HTTP_USER_AGENT"];
if (strpos($browser, "Safari"))
{
	$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "safari.css\" />";
	$cbox_height = "height: 383px;";
	$margin = "margin: 0px;";
	$footer_padding = "padding-top: 10px; ";
	$player_height = "186";
	$signup_padding = "padding: 0px 0px 0px 0px;";
}
	elseif (strpos($browser, "Gecko") && !strpos($browser, "Safari") or strpos($browser, "Firefox") or strpos($browser, "Netscape"))
	{
		if (strpos($browser, "Macintosh"))
		{
			$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "mozilla.css\" />";
			$cbox_height = "height: 383px;";
			$margin = "margin: 0px;";
			$footer_padding = "padding-top: 10px; ";
			$player_height = "186";
			$signup_padding = "padding: 1px 0px 0px 0px;";
		}
		if (strpos($browser, "Windows"))
		{
			$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "mozilla.css\" />";
			$cbox_height = "height: 405px;";
			$margin = "margin: 0px;";
			$footer_padding = "padding-top: 10px; ";
			$player_height = "186";
			$signup_padding = "padding: 1px 0px 0px 0px;";
		} 
	}
		elseif(strpos($browser, "Opera"))
		{
			if (strpos($browser, "Mac"))
			{
				$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "opera.css\" />";
				$cbox_height = "height: 389px;";
				$margin = "margin: 0px;";
				$footer_padding = "padding-top: 10px; ";
				$player_height = "186";
				$signup_padding = "padding: 1px 0px 0px 0px;";
			}
			if (strpos($browser, "Windows"))
			{
				$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "mozilla.css\" />";
				$cbox_height = "height: 405px;";
				$margin = "margin: 0px;";
				$footer_padding = "padding-top: 10px; ";
				$player_height = "186";
				$signup_padding = "padding: 1px 0px 0px 0px;";
			} 
		}
			else
			{
				$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "ie.css\" />";
				$cbox_height = "height: 408px;";
				$margin = "margin-top: -15px;";
				$footer_padding = "";
				$player_height = "196";
				$signup_padding = "padding: 1px 0px 0px 0px;";
			}
		
$page = $_GET["page"];

if ($page == "home")
{
	$include = "home.html";
}
	elseif ($page == "about")
	{
		$include = "about.html";
	}
		elseif ($page == "faq")
		{
			$include = "faq.html";
			$onload = " onload=\"preLoad();\"";
			$toggle_js = "<script src=\"" . JS_DIR . "toggle.js\" type=\"text/javascript\"></script>";
		}
			elseif ($page == "advertise")
			{
				$include = "advertise.html";
			}
				elseif ($page == "sell")
				{
					$include = "sell.html";
				}
					elseif ($page == "contact")
					{
						$include = "about.html";
					}
						else
						{
							$include = "home.html";
						}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>eikaiwa.fm | eigo.fm | english.fm</title>
	<meta name="generator" content="BBEdit 8.2" />
	<link rel="stylesheet" type="text/css" href="./css/eikaiwa.css" />
	<? print $css; ?>
	<? print $toggle_js; ?>
	<script src="<? print JS_DIR; ?>menu.js" type="text/javascript"></script>
	<script src="<? print JS_DIR; ?>checkit.js" type="text/javascript"></script>
</head>

<body<? print $onload; ?>>

<table cellpadding="0" cellspacing="0" border="0" class="main">
<tr>
	<td colspan="3"><img src="<? print IMG_DIR; ?>white-tab-top.gif" border="0" alt="" class="no_padding" /></td>
</tr>
<tr>
	<td colspan="3" class="main_banner"><a href="<? print INDEX_FILE; ?>"><img src="<? print IMG_DIR; ?>banner.gif" border="0" alt="" /></a></td>
</tr>
<tr>
	<!-- START OF MENU COLUMN -->
	<td class="main_menu">
		
		<!-- MENU CONTAINER TABLE -->
		<table cellpadding="0" cellspacing="0" class="menu_container">
		<tr>
			<td class="menu_container_top"></td>
		</tr>
		<tr>
			<td class="menu_container_middle">
				
				<? if ($display_greeting == true) { include_once(HTML_DIR . "logout.html"); } ?>
				
				<!-- MENU TABLE -->
				<div class="menu">
				<table cellpadding="0" cellspacing="0" class="menu">
				<tr>
					<td class="menu_top"></td>
				</tr>
				<tr>
					<td class="menu_middle">
						<?php include_once(HTML_DIR . "menu.html"); ?>
					</td>
				</tr>
				<tr>
					<td class="menu_bottom"></td>
				</tr>
				</table>
				</div>
				
				<!-- E-UPDATES TABLE -->
				<div class="eupdates">
				<table cellpadding="0" cellspacing="0" class="eupdates">
				<tr>
					<td class="eupdates_top"></td>
				</tr>
				<tr>
					<td class="eupdates_middle">
						<form name="updates" id="updates" action="" class="eupdates" onsubmit="return checkBox();">
						<table cellpadding="0" cellspacing="0" class="eupdates_a">
						<?php if (!empty($error_newsletter)){ ?>
						<tr>
							<td colspan="2"><?php print $error_newsletter; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan="2">ç°ì˙ÇÕ</td>
						</tr>
						<tr>
							<td class="eupdates_a_one"><img src="<? print IMG_DIR; ?>one.gif" border="" alt=""/></td>
							<td class="eupdates_a_email"><input type="text" name="updates_email" value="&nbsp;email address" onfocus="document.updates.updates_email.value = '';" class="eupdates_email"/></td>
						</tr>
						<tr>
							<td class="eupdates_a_two"><img src="<? print IMG_DIR; ?>two.gif" border="" alt=""/></td>
							<td class="eupdates_a_region">
								<select name="region" class="eupdates_region">
								<option value="" selected="selected">Select your region</option>
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
							</td>
						</tr>
						<tr>
							<td class="eupdates_a_three"></td>
							<td class="eupdates_a_submit"><input type=image src="<? print IMG_DIR; ?>sign_up.gif" border="0" name="" alt="" style="margin: 0px; <?print $signup_padding; ?>"/></td>
						</tr>
						</table>
						</form>
					</td>
				</tr>
				<tr>
					<td class="eupdates_bottom"></td>
				</tr>
				</table>
				</div>
				
			</td>
		</tr>
		<tr>
			<td class="menu_container_bottom"></td>
		</tr>
		</table>
	</td>
	<td colspan="2" class="main_content">
	<!-- START OF CONTENT COLUMN -->
	<? include_once(HTML_DIR . $include); ?>
	</td>
</tr>
<tr>
	<td colspan="3"><img src="<? print IMG_DIR; ?>white-tab-bottom.gif" border="0" alt="" class="no_padding"/></td>
</tr>
</table>
<a name="bottom"></a>
<? include_once(HTML_DIR . "footer.html"); ?>
	
</body>
</html>
