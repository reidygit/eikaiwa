<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><? echo getenv('HTTP_HOST'); ?> -- å‹é”ã«æ•™ãˆã‚ˆã†!</title>
	<meta name="generator" content="BBEdit 8.2" />
	
	<style type="text/css">
	a:link {color: #777; text-decoration: none}
	a:visited {color: #777; text-decoration: none}
	a:hover {color: #8C1414; text-decoration: none}
	a:active {color: #777; text-decoration: none}
	</style>
	
	<!-- OPENTRACKER HTML START -->
	<script defer
	src="http://server1.opentracker.net/?site=<? echo getenv('HTTP_HOST'); ?>"></script><noscript><a
	href="http://www.opentracker.net" target="_blank"><img
	src="http://img.opentracker.net/?cmd=nojs&site=<? echo getenv('HTTP_HOST'); ?>" alt="website
	counter" border="0"></a> </noscript>
	<!-- OPENTRACKER HTML END -->
	
	<script language="JavaScript" type="text/javascript">
	<!--
		function checkIt()
		{
			if (document.tell_friend.sender.value == null || document.tell_friend.sender.value.length == 0)
			{
				alert("please fill in your Email address");
				document.tell_friend.tell_friend.focus();
				return false;
			}			
			var regex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
			if (!document.tell_friend.sender.value.match(regex))
			{
				alert("please verify your Email address format.");
				document.tell_friend.sender.focus();
				return false;
			}
			if (document.tell_friend.femail.value == null || document.tell_friend.femail.value.length == 0)
			{
				alert("please fill in your Friend's Email address");
				document.tell_friend.femail.focus();
				return false;
			}		
			var regex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
			if (!document.tell_friend.femail.value.match(regex))
			{
				alert("please verify your Friend's Email address format.");
				document.tell_friend.femail.focus();
				return false;
			}
			if (document.tell_friend.yname.value == null || document.tell_friend.yname.value.length == 0)
			{
				alert("please fill in your Name");
				document.tell_friend.yname.focus();
				return false;
			}	
		}
	// -->
	</script>
	
	<!-- 
	tell_friend.html -> tell_friend.pl -> tell_friend_thanks.html
	//-->
	
</head>
<?
@session_start();
$action='tell_friend.php';	
$onload='document.tell_friend.sender.focus();';
if(isset($_POST['catchpa']))
{
if($_POST['catchpa']!=$_SESSION['secret_string'])
{
$action='tell_friend.php';
unset($_SESSION['secret_string']);
$onload='document.tell_friend.sender.focus();';
$error=1;
}
else
{
$msg='
Hi $$FriendName$$,
$$SMSG$$
I thought you might find this site interesting:

'.getenv('HTTP_HOST').'


Let me know what you think.

$$YourName$$
$$YourEmail$$
';


$subject='Invitation from $$YourName$$';

$from='admin@nihongo.fm';

$msg = str_replace('$$FriendName$$',$_POST['femail'],$msg);
$msg = str_replace('$$SMSG$$',$_POST['message'],$msg);
$msg = str_replace('$$YourName$$',$_POST['yname'],$msg);
$msg = str_replace('$$YourEmail$$',$_POST['sender'],$msg);

$subject = str_replace('$$YourName$$',$_POST['yname'],$subject);

mail($_POST['femail'], $subject, $msg, "From: $from");
$_GET['action'] = "thanks";

}
}
?>
<body onload="<? echo $onload ?>" style="font: 12px arial;">
<br/>
<?
	if ($_GET['action'] == "thanks")
	{
		print "<p style=\"text-align: center;\">Thanks for helping us spread the word about nihongo.fm.
Mail has been sent to: <i>" . $_POST['femail'] . "</i>)</p>";
	}
if($error==1)
{
?>
<p style="text-align: center; color:red;"><i><strong>Error :</strong> You provided invalid image validation code</i></p>
<?
}
?>
<form method="post" action="<? echo $action ?>" id="tell_friend" name="tell_friend" onsubmit="return checkIt();">
	<table cellspacing="0" style="margin: 0 auto; padding: 0px; text-align: center; font: 12px arial; border: 1px solid #C6C47B; background-color: #E7E3B5;">
	<tr>
		<td colspan="3" style="padding-top: 4px; padding-bottom: 4px;text-align: center; background-color: #fff; border-bottom: 1px solid #C6C47B; font-weight: bold; color: #8C1414;">Tell Your Friends about <? echo getenv('HTTP_HOST'); ?></td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-right: 8px; text-align: right; font-weight: bold; color: #555;">Your Email:</td>
		<td colspan="2" style="padding-right: 4px; padding-top: 6px;"><input type="text" value="<? if($error==1) { echo $_POST['sender']; } ?>" name="sender" style="width: 200px; border: 1px solid #C6C47B; color: #000; background-color: #fff;" /></td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-right: 8px; padding-bottom: 10px; text-align: right; font-weight: bold; color: #555;">Your Name:</td>
		<td colspan="2" style="padding-right: 4px; padding-bottom: 10px;"><input type="text" name="yname" style="width: 200px; border: 1px solid #C6C47B; color: #000; background-color: #fff;" value="<? if($error==1) { echo $_POST['yname']; } ?>" /></td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-right: 8px; text-align: right; font-weight: bold; color: #555;">Friend's Email:</td>
		<td colspan="2" style="padding-right: 4px;"><input type="text" name="femail" style="width: 200px; border: 1px solid #C6C47B; color: #000; background-color: #fff;" value="<? if($error==1) { echo $_POST['femail']; } ?>" /></td>
	</tr>
	<tr>
	  <td style="padding-left: 4px; padding-top: 7px; padding-right: 8px; vertical-align: top; text-align: right; font-weight: bold; color: #555;">Enter Text in Image </td>
	  <td style="padding-right: 4px;"><img src="catchpa.php" alt="CATXHPA" /></td>
	  <td style="padding-right: 4px;"><input name="catchpa" type="text" size="5" id="catchpa" /></td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-top: 7px; padding-right: 8px; vertical-align: top; text-align: right; font-weight: bold; color: #555;">Quick Message:</td>
		<td colspan="2" style="padding-right: 4px;"><textarea rows="6" name="message" style="width: 200px; margin: 0px; border: 1px solid #C6C47B; color: #000; background-color: #fff;" ><? if($error==1) { echo $_POST['message']; } ?></textarea></td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 6px; padding-bottom: 6px;"><input type="submit" value="Tell a Friend" /></td>
	</tr>
	</table>
</form>

<p style="text-align: center;"><a href="javascript:window.close();">close window</a></p>

</body>
</html>
