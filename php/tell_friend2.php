<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><? echo getenv('HTTP_HOST'); ?> - 友達紹介！</title>
	<meta name="generator" content="BBEdit 8.2" />
	
	<style type="text/css">
	a:link {color: #777; text-decoration: none}
	a:visited {color: #777; text-decoration: none}
	a:hover {color: #8C1414; text-decoration: none}
	a:active {color: #777; text-decoration: none}
	</style>
	
	<script language="JavaScript" type="text/javascript">
	<!--
		function checkIt()
		{
			if (document.tell_friend.sender.value == null || document.tell_friend.sender.value.length == 0)
			{
				alert("あなたのメールを入力して下さい。");
				document.tell_friend.tell_friend.focus();
				return false;
			}			
			var regex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
			if (!document.tell_friend.sender.value.match(regex))
			{
				alert("あなたのメールをチェックして下さい。");
				document.tell_friend.sender.focus();
				return false;
			}
			if (document.tell_friend.femail.value == null || document.tell_friend.femail.value.length == 0)
			{
				alert("友達のメールを入力して下さい。");
				document.tell_friend.femail.focus();
				return false;
			}		
			var regex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
			if (!document.tell_friend.femail.value.match(regex))
			{
				alert("友達のメールをチェックして下さい。");
				document.tell_friend.femail.focus();
				return false;
			}
			if (document.tell_friend.yname.value == null || document.tell_friend.yname.value.length == 0)
			{
				alert("友達の名前を入力して下さい");
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

$from='admin@eikaiwa.fm';

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
		print "<p style=\"text-align: center;\">ありがとうございます! 
友達にメールを送りました: (<i>" . $_POST['femail'] . "</i>)</p>";
	}
if($error==1)
{
?>
<p style="text-align: center; color:red;"><i><strong>エラ:</strong> You provided invalid image validation code</i></p>
<?
}
?>
<form method="post" action="<? echo $action ?>" id="tell_friend" name="tell_friend" onsubmit="return checkIt();">
	<table cellspacing="0" style="margin: 0 auto; padding: 0px; text-align: center; font: 12px arial; border: 1px solid #C6C47B; background-color: #E7E3B5;">
	<tr>
		<td colspan="3" style="padding-top: 4px; padding-bottom: 4px;text-align: center; background-color: #fff; border-bottom: 1px solid #C6C47B; font-weight: bold; color: #8C1414;"><? echo getenv('HTTP_HOST'); ?>について友達を教えて!</td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-right: 8px; text-align: right; font-weight: bold; color: #555;">あなたのメール:</td>
		<td colspan="2" style="padding-right: 4px; padding-top: 6px;"><input type="text" value="<? if($error==1) { echo $_POST['sender']; } ?>" name="sender" style="width: 200px; border: 1px solid #C6C47B; color: #000; background-color: #fff;" /></td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-right: 8px; padding-bottom: 10px; text-align: right; font-weight: bold; color: #555;">あなたの名前:</td>
		<td colspan="2" style="padding-right: 4px; padding-bottom: 10px;"><input type="text" name="yname" style="width: 200px; border: 1px solid #C6C47B; color: #000; background-color: #fff;" value="<? if($error==1) { echo $_POST['yname']; } ?>" /></td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-right: 8px; text-align: right; font-weight: bold; color: #555;">友達のメール:</td>
		<td colspan="2" style="padding-right: 4px;"><input type="text" name="femail" style="width: 200px; border: 1px solid #C6C47B; color: #000; background-color: #fff;" value="<? if($error==1) { echo $_POST['femail']; } ?>" /></td>
	</tr>
	<tr>
	  <td style="padding-left: 4px; padding-top: 7px; padding-right: 8px; vertical-align: top; text-align: right; font-weight: bold; color: #555;">数字を入力して下さい:</td>
	  <td style="padding-right: 4px;"><img src="catchpa.php" alt="CATXHPA" /></td>
	  <td style="padding-right: 4px;"><input name="catchpa" type="text" size="5" id="catchpa" /></td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-top: 7px; padding-right: 8px; vertical-align: top; text-align: right; font-weight: bold; color: #555;">メッセージ:</td>
		<td colspan="2" style="padding-right: 4px;"><textarea rows="6" name="message" style="width: 200px; margin: 0px; border: 1px solid #C6C47B; color: #000; background-color: #fff;" ><? if($error==1) { echo $_POST['message']; } ?></textarea></td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 6px; padding-bottom: 6px;"><input type="submit" value="Tell a Friend" /></td>
	</tr>
	</table>
</form>

<p style="text-align: center;"><a href="javascript:window.close();">閉める</a></p>

</body>
</html>
