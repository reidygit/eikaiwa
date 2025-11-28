<?php
include_once("../php/ez_sql.php");
include_once("podcast_class.php");

//print_r($_POST);

$channel = $_POST["channel_id"];
$action = $_POST["action"];

function getTitles($channel_id)
{
	global $db;
	$query = "SELECT podcast_item.item_id, podcast_item.title FROM podcast_item WHERE podcast_item.channel_id = '$channel_id'";
	return($db->get_results($query));
}
function itemSelector($channel_id)
{
	$titles = getTitles($channel_id);
	$string .= "<select name=\"item_id\">\n";
	foreach ($titles as $title)
	{
		$string .= "\t<option value=\"" . $title->item_id . "\">" . $title->title . "</option>\n";
	}
	$string .= "</select>\n";
	print $string;
}
function getDetails($item_id)
{
	global $db;
	$query = "SELECT podcast_item.item_id, 
			podcast_item.title, 
			podcast_item.description, 
			podcast_item.filename, 
			podcast_item.`itunes:author`, 
			podcast_item.`itunes:category`, 
			podcast_item.`itunes:explicit`, 
			podcast_item.`itunes:subtitle`, 
			podcast_item.`itunes:summary`, 
			podcast_item.`itunes:duration`, 
			podcast_item.`itunes:keywords`
			FROM podcast_item
			WHERE podcast_item.item_id = '$item_id'";
	return($db->get_row($query, ARRAY_N));
}

function deleteItem($item_id)
{
	global $db;
	$query = "DELETE FROM podcast_item WHERE podcast_item.item_id = '$item_id'";
	$db->query($query);
}

$podcast = new podCast();
if ($_POST["channel_id"] == 1)
{
	$podcast->url = "http://nihongo.fm/";
	$podcast->p_media = "../audio/podcast/";
	$podcast->img_dir = "img/";
}
	elseif ($_POST["channel_id"] == 2)
	{
		$podcast->url = "http://eikaiwa.fm/";
	$podcast->p_media = "../audio/podcast/";
	$podcast->img_dir = "img/";
	}

switch($action) 
{
	case edit_item:
	include_once("podcast_menu.html");
	include_once("podcast_item.html");
	break;
	
	case add:
	include_once("podcast_menu.html");
	include_once("podcast_item.html");
	break;
	
	case del_item:
	deleteItem($_POST["item_id"]);
	$podcast->writePodcast($_POST["channel_id"]);
	include_once("podcast_menu.html");
	break;
	
	case update_item:
	$podcast->updateItem($_POST);
	$podcast->writePodcast($_POST["channel_id"]);
	include_once("podcast_menu.html");
	break;
	
	case new_item:
	//print "hello";
	$podcast->newItem($_POST);
	$podcast->writePodcast($_POST["channel_id"]);
	include_once("podcast_menu.html");
	break;
	
	case edit_channel:
	$details = $podcast->getChannelDetails($_POST["channel_id"]);
	//print_r($details);
	include_once("podcast_menu.html");
	include_once("podcast_channel.html");
	break;
	
	case update_channel:
	$podcast->updateChannel($_POST);
	$podcast->writePodcast($_POST["channel_id"]);
	include_once("podcast_menu.html");
	break;
	
	default:
	include_once("podcast_menu.html");
	//print "default";
}

?>