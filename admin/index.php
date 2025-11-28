<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Untitled</title>
	<meta name="generator" content="BBEdit 8.2" />
</head>
<body>

<table cellspacing="0" style="padding: 0px; text-align: left; font-family: arial; font-size: 12px;">
<tr>
	<td style="padding: 4px 0px 10px 0px; text-align: left; font-size: 14px; font-weight: bold;">eikaiwa.fm admin</td>
</tr>
<tr>
	<td style="padding: 4px 0px 10px 0px; text-align: left;"><a href="index.php?action=addresses">get addresses</a>&nbsp;|&nbsp;<a href="index.php?action=templates">edit templates</a></td>
</tr>
<tr>
	<td>
	<?
		$action = $_GET['action'];
		if ($action == "addresses")
		{
			include_once("get_addresses.php");
		}
		if ($action == "templates")
		{
			include_once("edit_template.php");
		}
	?>
	</td>
</tr>
</table>

</body>
</html>
