<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>files</title>
	<meta name="generator" content="BBEdit 8.2" />
</head>

<style type="text/css">

#dhtmltooltip
{
	position: absolute;
	width: 150px;
	border: 1px solid black;
	padding: 2px;
	font-size: 12px;
	font-family: arial, helvetica, sans-serif;
	background-color: lightyellow;
	visibility: hidden;
	z-index: 100;
	/*Remove below line to remove shadow. Below line should always appear last within this CSS*/
	filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135);
}

a:link {text-decoration: none; color: #8C1414;}     /* unvisited link */
a:visited {text-decoration: none; color: #8C1414;}  /* visited link */
a:hover {text-decoration: none; color: #F93;}   /* mouse over link */
a:active {text-decoration: none; color: #8C1414;}   /* selected link */

</style>

<body>

<div id="dhtmltooltip"></div>

<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody()
{
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth)
{
	if (ns6||ie)
	{
		if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
		if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
		tipobj.innerHTML=thetext
		enabletip=true
		return false
	}
}

function positiontip(e)
{
	if (enabletip)
	{
		var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
		var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
		//Find out how close the mouse is to the corner of the window
		var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
		var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20
		
		var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000
		
		//if the horizontal distance isn't enough to accomodate the width of the context menu
		if (rightedge<tipobj.offsetWidth)
		//move the horizontal position of the menu to the left by it's width
		tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
		else if (curX<leftedge)
		tipobj.style.left="5px"
		else
		//position the horizontal position of the menu where the mouse is positioned
		tipobj.style.left=curX+offsetxpoint+"px"
		
		//same concept with the vertical position
		if (bottomedge<tipobj.offsetHeight)
		tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
		else
		tipobj.style.top=curY+offsetypoint+"px"
		tipobj.style.visibility="visible"
	}
}

function hideddrivetip()
{
	if (ns6||ie)
	{
		enabletip=false
		tipobj.style.visibility="hidden"
		tipobj.style.left="-1000px"
		tipobj.style.backgroundColor=''
		tipobj.style.width=''
	}
}

document.onmousemove=positiontip

</script>


<table cellspacing="0" style="margin: 0 auto; font-family: arial, helvetica, sans-serif; font-size: 14px; color: #777">
<tr>
	<td style="padding: 0px 12px 0px 2px; font-weight: bold; color: #000; border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000">Date</td>
	<td style="padding: 0px 8px 0px 0px; font-weight: bold; color: #000; border-top: 1px solid #000; border-bottom: 1px solid #000">File</td>
	<td style="padding: 0px 2px 0px 0px; font-weight: bold; color: #000; border-top: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000">Size</td>
</tr>
<tr>
	<td colspan="3" style="padding: 2px 0px 0px 0px;"></td>
</tr>
<?php

include_once("../class/ez_sql.php");

$dir = "../audio_uploads/";

if ($handle = opendir($dir)) 
{
	while (false !== ($file = readdir($handle))) 
	{
		if ($file != "." && $file != ".." && $file != ".DS_Store") 
		{
			global $db;
			$query = "SELECT email, comment FROM audio_uploads WHERE file = '$file'";
			$row = $db->get_row($query);
			
			$text = "<b>from:</b> $row->email <br/><br/> <b>message:</b> $row->comment";
			
			echo "<tr>";
			echo "<td style=\"padding: 0px 12px 0px 2px\">" . date("Y-m-d", filectime($dir . $file)) . "</td>";
			echo "<td style=\"padding: 0px 8px 0px 0px\">" . "<a href=\"$dir" . $file . "\" onMouseover=\"ddrivetip('$text','#cccccc', 300)\"; onMouseout=\"hideddrivetip()\">" . $file . "</a>" . "</td>";
			echo "<td style=\"padding: 0px 2px 0px 0px\">" . ceil(filesize($dir . $file)/1024) . "kb</td>";
			echo "</tr>";
		}
	}
	closedir($handle);
}

?>
</table>
</body>
</html>
