<?php
session_start();

//print_r($_SESSION);

include_once("./php/ez_sql.php");

$email_address = $_POST['newsletter_email_address'];
$region = $_POST['newletter_region'];

if (!empty($email_address) && !empty($region))
{
	global $db;
	$db->hide_errors();

	$prevlocation = rawurlencode("http://www.eikaiwa.fm" . $_SERVER['REQUEST_URI']);
	
	if ($db->query("INSERT INTO newsletter_mailing_list (email_address,region) VALUES ('$email_address','$region')"))
	{
		//newsletter_signup_email($email_address);
		// redirect to send email
		$_SESSION['response_message'] = "Thanks for signing up.";
		header("Location: http://www.eikaiwa.fm/cgi-bin/newsletter.pl?type=signup&email=$email_address&prevlocation=$prevlocation");
		exit;
	}
		elseif ($db->get_var("SELECT status FROM newsletter_mailing_list WHERE email_address = '$email_address'") == 0)
		{
			$db->query("UPDATE newsletter_mailing_list SET status = 1 WHERE email_address = '$email_address'");
			//newsletter_signup_email($email_address);
			// redirect to send email
			$_SESSION['response_message'] = "Welcome back to the mailing list.";
			header("Location: http://www.eikaiwa.fm/cgi-bin/newsletter.pl?type=signup&email=$email_address&prevlocation=$prevlocation");
			exit;
		}
			else
			{
				$response_message = "The email address you supplied is already signed up.";
			}
}	

define("HTML_DIR", "./html/");
define("IMG_DIR", "./img/");
define("CSS_DIR", "./css/");
define("JS_DIR", "./javascript/");
define("PHP_DIR", "./php/");
define("INDEX_FILE", "index.php");
define("PODCAST_LINK", "podcast.xml");
define("STORE_URL", "http://store.eikaiwa.fm/index.php");
define("FREE_PLAYER_URL", "http://www.eikaiwa.fm/php/player.php?channel=eikaiwa_free");
define("PREMIUM_PLAYER_URL", "http://www.eikaiwa.fm/php/player.php?channel=eikaiwa_premium");
define("FREE_CURRENT_TRACK", "http://68.178.149.70/current_track.php&#063;playlist=eikaiwafreestream&amp;output=index");
define("PREMIUM_CURRENT_TRACK", "http://68.178.149.70/current_track.php&#063;playlist=eikaiwapremiumstream&amp;output=index");

//include_once(PHP_DIR . "authenticate.php");
//include_once(PHP_DIR . "e_updates.php");

$browser = $_SERVER["HTTP_USER_AGENT"];
if (strpos($browser, "Safari"))
{
	$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "safari.css\" />";
	$cbox_height = "height: 411px;";
	$margin = "margin: 0px;";
	$footer_padding = "padding-top: 10px; ";
	$player_height = "186";
	$signup_padding = "padding: 0px 0px 0px 0px;";
	$advertise_ol = "padding: 6px 0px 0px 20px;";
	$eupdates_margin = "margin: 9px 0px 0px 0px;";
	$unsubscribe_height = "210";
}
	elseif (strpos($browser, "Gecko") && !strpos($browser, "Safari") or strpos($browser, "Firefox") or strpos($browser, "Netscape"))
	{
		if (strpos($browser, "Macintosh"))
		{
			$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "mozilla.css\" />";
			$cbox_height = "height: 411px;";
			$margin = "margin: 0px;";
			$footer_padding = "padding-top: 10px; ";
			$player_height = "186";
			$signup_padding = "padding: 1px 0px 0px 0px;";
			$advertise_ol = "padding: 6px 0px 0px 23px;";
			$eupdates_margin = "margin: 9px 0px 0px 0px;";
			$unsubscribe_height = "210";
		}
		if (strpos($browser, "Windows"))
		{
			$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "mozilla.css\" />";
			$cbox_height = "height: 435px;";
			$margin = "margin: 0px;";
			$footer_padding = "padding-top: 10px; ";
			$player_height = "186";
			$signup_padding = "padding: 1px 0px 0px 0px;";
			$advertise_ol = "padding: 6px 0px 0px 24px;";
			$eupdates_margin = "margin: 9px 0px 0px 0px;";
			$unsubscribe_height = "210";
		} 
	}
		elseif(strpos($browser, "Opera"))
		{
			if (strpos($browser, "Mac"))
			{
				$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "opera.css\" />";
				$cbox_height = "height: 417px;";
				$margin = "margin: 0px;";
				$footer_padding = "padding-top: 10px; ";
				$player_height = "186";
				$signup_padding = "padding: 1px 0px 0px 0px;";
				$advertise_ol = "padding: 6px 0px 0px 20px;";
				$eupdates_margin = "margin: 9px 0px 0px 0px;";
				$unsubscribe_height = "210";
			}
			if (strpos($browser, "Windows"))
			{
				$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "mozilla.css\" />";
				$cbox_height = "height: 433px;";
				$margin = "margin: 0px;";
				$footer_padding = "padding-top: 10px; ";
				$player_height = "186";
				$signup_padding = "padding: 1px 0px 0px 0px;";
				$advertise_ol = "padding: 6px 0px 0px 20px;";
				$eupdates_margin = "margin: 9px 0px 0px 0px;";
				$unsubscribe_height = "210";
			} 
		}
			else
			{
				$css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CSS_DIR . "ie.css\" />";
				$cbox_height = "height: 438px;";
				$margin = "margin-top: -15px;";
				$footer_padding = "";
				$player_height = "196";
				$signup_padding = "padding: 1px 0px 0px 0px;";
				$advertise_ol = "padding: 6px 0px 0px 29px;";
				$eupdates_margin = "margin: 6px 0px 0px 0px;";
				$unsubscribe_height = "226";
			}
		
$page = $_GET["page"];

if ($page == "home")
{
	$include = "home.html";
	$ad_page = "ads_index.html";
}
	elseif ($page == "about")
	{
		$include = "about.html";
		$ad_page = "ads_about.html";
	}
		elseif ($page == "faq")
		{
			$include = "faq.html";
			//$onload = " onload=\"preLoad();\"";
			$onload = "";
			$toggle_js = "<script src=\"" . JS_DIR . "toggle.js\" type=\"text/javascript\"></script>";
			$ad_page = "ads_faq.html";
		}
			elseif ($page == "advertise")
			{
				$include = "advertise.html";
				$ad_page = "ads_advertise.html";
			}
				elseif ($page == "sell")
				{
					$include = "sell.html";
					$ad_page = "ads_sell.html";
				}
					elseif ($page == "contact")
					{
						$include = "about.html";
						$ad_page = "ads_advertise.html";
					}
						else
						{
							$include = "home.html";
							$ad_page = "ads_index.html";
						}

function hex_encode($email_address) 
{
	$string = '';
	for($i = 0; $i < strlen($email_address); $i++) 
	{
       		$string .= '&#x'.bin2hex(substr($email_address, $i, 1)).';';
       	}
   	return ($string);
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
				
				<div style="margin-top: 9px; width: 180px; height: 25px; text-align: left; background-image: url(<? print IMG_DIR; ?>nihongo_tab.gif); background-repeat: no-repeat;"><p style="margin:0px; padding: 8px 0px 0px 8px; font: bold 12px arial; color: #fff;">フリーサンプル (毎週更新中)</p></div>
				<div style="width: 178px; border-left: 1px solid #C6C47B; border-right: 1px solid #C6C47B; background: #fff;"><p style="margin:0px; padding: 4px 8px 0px 8px; font: 12px arial; text-align: center;">list</p></div>
				<div style="margin-bottom: 0px; width: 180px; height: 10px; text-align: left; background-image: url(<? print IMG_DIR; ?>menu-tab-bottom.gif); background-repeat: no-repeat;"></div>
				
				<!-- E-UPDATES TABLE -->
				<div style="<? print $eupdates_margin; ?>">
				<table cellpadding="0" cellspacing="0" class="eupdates">
				<tr>
					<td class="eupdates_top"><p style="margin:0px; padding: 6px 0px 0px 8px; font: bold 12px arial; color: #fff;">メールマガジン</p></td>
				</tr>
				<tr>
					<td class="eupdates_middle">
						<form name="updates" id="updates" method="post" class="eupdates" onsubmit="return checkBox();">
						<table cellpadding="0" cellspacing="0" class="eupdates_a">
						<?php if (!empty($response_message)){ ?>
						<tr>
							<td colspan="2"><?php print $response_message; ?><? unset($_SESSION['response_message']); ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan="2"><p style="margin: 0px; padding: 0px 0px 0px 2px;">eikaiwa.fmのアップデートやイベント情報等を配信します！</p></td>
						</tr>
						<tr>
							<td class="eupdates_a_one"><img src="<? print IMG_DIR; ?>one.gif" border="" alt=""/></td>
							<td class="eupdates_a_email"><input type="text" name="newsletter_email_address" value="&nbsp;メールアドレス" onfocus="document.updates.newsletter_email_address.value = '';" class="eupdates_email"/></td>
						</tr>
						<tr>
							<td class="eupdates_a_two"><img src="<? print IMG_DIR; ?>two.gif" border="" alt=""/></td>
							<td class="eupdates_a_region">
								<select name="newletter_region" class="eupdates_region">
								<option value="" selected="selected">地域を選択して下さいサインアップ!</option>
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
				
				<div style="margin-top: 9px; width: 180px; height: 25px; text-align: left; background-image: url(<? print IMG_DIR; ?>nihongo_tab.gif); background-repeat: no-repeat;"><p style="margin:0px; padding: 8px 0px 0px 8px; font: bold 12px arial; color: #fff;">Sponsors</p></div>
				<div style="width: 178px; border-left: 1px solid #C6C47B; border-right: 1px solid #C6C47B; background: #fff;"><p style="margin:0px; padding: 4px 8px 0px 8px; font: 12px arial; text-align: center;"><? include_once(HTML_DIR . $ad_page); ?></p></div>
				<div style="margin-bottom: 9px; width: 180px; height: 10px; text-align: left; background-image: url(<? print IMG_DIR; ?>menu-tab-bottom.gif); background-repeat: no-repeat;"></div>		
	
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
