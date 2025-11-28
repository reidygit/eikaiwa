<? 

include_once("../php/ez_sql.php");

global $db;

if (!empty($_POST['template'])) 
{
	$type = $_POST['template'];
	$query = "SELECT subject,body FROM newsletter_templates WHERE type = '$type'";
	$row = $db->get_row($query);
}

if (!empty($_POST['subject']) && !empty($_POST['message']) && !empty($_POST['type'])) 
{
	$type = $_POST['type'];
	$subject = addslashes($_POST['subject']);
	$body = addslashes($_POST['message']);
	$query = "UPDATE newsletter_templates SET subject = '$subject', body = '$body' WHERE type = '$type'";
	$db->query($query);
	$response_message = $type;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Edit newsletter templates</title>
	<meta name="generator" content="BBEdit 8.2" />
</head>
<body>

<form name="choose_template" id="choose_template" method="post" style="text-align: left;">

<select name="template" onchange="this.form.submit();">
	<option value="" selected="selected">Select a template</option>
	<option value="signup">Signup template</option>
	<option value="cancel">Cancel template</option>
</select>

</form>

<? if (!empty($_POST['template'])) { ?>
Currently editing: <font style="font-weight: bold; color: red;"><? print $_POST['template']; ?></font> template.<br/><br/>
<? } ?>

<? if (!empty($response_message)) { ?>
You have sucsessfully changed the <font style="font-weight: bold; color: red;"><? print $response_message; ?></font> template.<br/><br/>
<? } ?>

<form name="edit_template" id="edit_template" method="post" style="text-align: left;">
<input type="hidden" name="type" id="type" value="<? print $_POST['template']; ?>"/>
subject:
<br/>
<input type="text" name="subject" id="subject" value="<? print stripslashes($row->subject); ?>" style="width: 500px;"/>
<br/>
message:
<br/>
<textarea name="message" id="message" style="width: 500px; height: 600px;"><? print stripslashes($row->body); ?></textarea>
<br/>
<input type="submit" value="save"/>

</form>

</body>
</html>
