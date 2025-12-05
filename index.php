<?php
session_start();

//print_r($_SESSION);

include_once("./php/ez_sql.php");

$email_address = isset($_POST['newsletter_email_address']) ? $_POST['newsletter_email_address'] : '';
$region = isset($_POST['newletter_region']) ? $_POST['newletter_region'] : '';

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
define("STORE_URL", "https://amzn.to/2LDkcpD");
// OLD STORE URL WAS: define("STORE_URL", "http://store.eikaiwa.fm/index.php");
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
	$cbox_height = "min-height: 400px;";
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
			$cbox_height = "min-height: 400px;";
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
			$cbox_height = "min-height: 400px;";
			$margin = "margin: 0px;";
			$footer_padding = "padding-top: 10px; ";
			$player_height = "196";
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
				$cbox_height = "min-height: 400px;";
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
				$cbox_height = "min-height: 400px;";
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
				$cbox_height = "min-height: 400px;";
				$margin = "margin-top: -15px;";
				$footer_padding = "";
				$player_height = "206";
				$signup_padding = "padding: 1px 0px 0px 0px;";
				$advertise_ol = "padding: 6px 0px 0px 29px;";
				$eupdates_margin = "margin: 6px 0px 0px 0px;";
				$unsubscribe_height = "226";
			}

$page = isset($_GET["page"]) ? $_GET["page"] : '';

// Initialize variables with defaults
$toggle_js = '';
$display_greeting = false;
$onload = '';

if ($page == "home")
{
	$include = "home.html";
	$ad_page = "ads_index.html";
	$onload = "onLoad=\"MM_preloadImages('" . IMG_DIR . "EIKAWAfm-590x250-ALT_01.jpg','" . IMG_DIR . "EIKAWAfm-590x250-ALT_03.jpg')\"";
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
							$onload = "onLoad=\"MM_preloadImages('" . IMG_DIR . "EIKAWAfm-590x250-ALT_01.jpg','" . IMG_DIR . "EIKAWAfm-590x250-ALT_03.jpg')\"";
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
	<title>無料英会話・英語ラジオ & mp3ダウンロード - eikaiwa.fm</title>
	<meta name="generator" content="BBEdit 8.2" />
	<meta name="keywords" content="英語ラジオ,英語リスニング,英語レッスン,NHK,英語勉強,英会話教師,個人英会話,英会話リスニング,英語語学,英会話学習,プライベートレッスン,教師,先生">
		<meta name="description" content="24時間無料の英語ラジオやiPod用の英語勉強MP3ダウンロード。今すぐ聴きましょう！">
		<meta name="copyright" content="Copyright 2006 - 2016. (有限会社英会話ドットエフエム) eikaiwa.fm, Ltd. (R. Greco)">
	<link rel="stylesheet" type="text/css" href="./css/eikaiwa.css?v=20251205" />
	<? print $css; ?>
	<? print $toggle_js; ?>
	<script src="<? print JS_DIR; ?>menu.js" type="text/javascript"></script>
	<script src="<? print JS_DIR; ?>checkit.js" type="text/javascript"></script>
	<script src="<? print JS_DIR; ?>banner.js" type="text/javascript"></script>
	<!-- META4 SIDE BANNER START -->
	<script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
</script>
<script type='text/javascript'>
GS_googleAddAdSenseService("ca-pub-9684247948196378");
GS_googleEnableAllServices();
</script>
<script type='text/javascript'>
GA_googleAddSlot("ca-pub-9684247948196378", "HM_AU_EKW_square");
</script>
<script type='text/javascript'>
GA_googleFetchAds();
</script>
	<!-- META4 FRIENDS SIDE BANNER END -->
	
	<!-- META4 FRIENDS TOP BANNER START -->
	<script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
</script>
<script type='text/javascript'>
GS_googleAddAdSenseService("ca-pub-9684247948196378");
GS_googleEnableAllServices();
</script>
<script type='text/javascript'>
GA_googleAddSlot("ca-pub-9684247948196378", "HM_AU_EKW_bar");
</script>
<script type='text/javascript'>
GA_googleFetchAds();
</script>
	<!-- META4 FRIENDS TOP BANNER START -->

	<style type="text/css">
	#dhtmltooltip
	{
		position: absolute;
		width: 150px;
		border: 1px solid #C6C47B;
		padding: 2px;
		font-size: 12px;
		font-family: arial, helvetica, sans-serif;
		background-color: lightyellow;
		visibility: hidden;
		z-index: 100;
		/* Remove below line to remove shadow. Below line should always appear last within this CSS */
		/* filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135); */
	}
	</style>
	
	
	
</head>

<body<? print $onload; ?>>

<div id="dhtmltooltip"></div>

<script src="./javascript/tooltip.js" type="text/javascript"></script>

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
				
				<div style="<? print $eupdates_margin; ?>">
				
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
				
				<div style="margin-top: 9px; width: 180px; height: 25px; text-align: left; background-image: url(<? print IMG_DIR; ?>menu-rc1a.gif); background-repeat: no-repeat;"><p style="margin:0px; padding: 8px 0px 0px 8px; font: bold 12px arial; color: #fff;">友達に教えよう!</p></div>
				<div style="width: 178px; border-left: 1px solid #C6C47B; border-right: 1px solid #C6C47B; background: #fff;">
					<p style="margin:0px; padding: 4px 8px 0px 8px; font: 11px arial; text-align: center;">
						英会話を勉強してる<br/>友達いませんか？<br/>
						<a href="http://www.eikaiwa.fm/php/tell_friend.php" target="newWindow" onclick="javascript: secondwindow = open('http://www.eikaiwa.fm/php/tell_friend.php', 'low', 'height=375,width=350,scrollbars=no'); return false"><input type="button" value="友達に教える"></a>
					</p>
					

				</div>
				<div style="margin-bottom: 9px; width: 180px; height: 10px; text-align: left; background-image: url(<? print IMG_DIR; ?>menu-tab-bottom.gif); background-repeat: no-repeat;"></div>
				<div style="margin-top: 9px; width: 180px; height: 25px; text-align: left; background-image: url(<? print IMG_DIR; ?>menu-rc1a.gif); background-repeat: no-repeat;"><p style="margin:0px; padding: 8px 0px 0px 8px; font: bold 12px arial; color: #fff;">スポンサー</p></div>
				<div style="width: 178px; border-left: 1px solid #C6C47B; border-right: 1px solid #C6C47B; background: #fff;"><p style="margin:0px; padding: 4px 8px 0px 8px; font: 12px arial; text-align: center;"><? include_once(HTML_DIR . $ad_page); ?></p></div>
				<div style="margin-bottom: 9px; width: 180px; height: 10px; text-align: left; background-image: url(<? print IMG_DIR; ?>menu-tab-bottom.gif); background-repeat: no-repeat;"></div>		
<!-- E-UPDATES TABLE -->
<center><table cellpadding="0" cellspacing="0" class="eupdates">
				<tr>
				<td>
					<a href="http://gaijinfriends.com" target="_blank"><img src="http://www.senseinavi.com/images/party-banner-sn.gif" width="180px"></a>
				</td>

				</tr>

				<tr>
					<td>

						

					</td>
				</tr>

				</table></center><br>

<center><table cellpadding="0" cellspacing="0" class="eupdates">
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
							<td colspan="2"><p style="margin: 0px; padding: 0px 0px 0px 2px;"><strong>eikaiwa.fm</strong>のアップデートやイベント情報等を配信します！</p></td>
						</tr>
						<tr>
							<td class="eupdates_a_one"><img src="<? print IMG_DIR; ?>one.gif" border="" alt=""/></td>
							<td class="eupdates_a_email"><input type="text" name="newsletter_email_address" value="&nbsp;メールアドレス" onfocus="document.updates.newsletter_email_address.value = '';" class="eupdates_email"/></td>
						</tr>
						<tr>
							<td class="eupdates_a_two"><img src="<? print IMG_DIR; ?>two.gif" border="" alt=""/></td>
							<td class="eupdates_a_region">
								<select name="newletter_region" class="eupdates_region">
								<option value="" selected="selected">地域を選択して下さい</option>
								<option value="hokkaido">北海道</option>
								<option value="tohoku">東北</option>
								<option value="kanto">関東</option>
								<option value="chubu">中部</option>
								<option value="kinki">近畿</option>
								<option value="chugoku">中国地方</option>
								<option value="shikoku">四国</option>
								<option value="kyushu">九州/沖縄</option>
								<option value="outside">日本国外</option>
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

	
		<!-- PODCAST BEGIN -->
				<!-- <p style="text-align: center;"><a href="http://www.eikaiwa.fm/podcast.xml"><img src="<? print IMG_DIR; ?>pod.gif" alt="" border="0"/></a></p> -->
				<!-- PODCAST END -->
	
		<!-- DONATE BEGIN-->
		
		<!--	<center><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="reid@eikaiwa.fm">
<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></center>  -->
				
				<!-- Donate End -->
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
	
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VC0K67STWY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VC0K67STWY');
</script>
<!-- Howler.js Audio Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js"></script>
<!-- Eikaiwa.fm Radio Player -->
<link rel="stylesheet" type="text/css" href="<? print CSS_DIR; ?>radio-player.css">
<script src="<? print JS_DIR; ?>radio-player.js"></script>
</body>
</html>
