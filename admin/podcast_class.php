<?php

include_once("../php/ez_sql.php");

class podCast
{
	
	var $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
	var $rss_open = "<rss xmlns:itunes=\"http://www.itunes.com/DTDs/Podcast-1.0.dtd\" version=\"2.0\">\n";
	var $rss_close = "</rss>\n";
	var $channel_open = "\t<channel>\n\n";
	var $channel_close = "\t</channel>\n";
	var $url;
	var $p_media;
	var $img_dir;
	var $type = "audio/mpeg";
		
	function newItem($data)
	{
		//print_r($data);
		
		// gets rid of the action value
		array_shift($data);
		
		// seperates the post keys from the post values
		$columns = array_keys($data);
		$values = array_values($data);
		
		// adds the key pubDate and value to the arrays
		array_unshift($columns, "pubDate");
		array_unshift($values, date("D, d M Y G:i:s O"));
		
		// formats the column array with the proper sql syntax
		foreach ($columns as $column)
		{
			$arr_column[] = "`" . $column . "`";
		}
		
		// formats the value array with the proper sql syntax
		foreach ($values as $value)
		{
			if ($value != "length")
			{
				$arr_value[] = "'" . $value . "'";
			}
				else
				{
					$arr_value[] = $value;
				}
		}
		
		// makes the query key/value strings
		$comma_separated_columns = implode(",", $arr_column);
		$comma_separated_values = htmlspecialchars(implode(",", $arr_value));
		
		// enters the data into the db
		global $db;
		$query = "INSERT INTO podcast_item($comma_separated_columns) VALUES($comma_separated_values)";
		$db->query($query);
	}
	
	function updateItem($data)
	{
		global $db;
		foreach ($data as $key=>$value)
		{
			if ($key != "action" && $key != "item_id")
			{
				$string .= "podcast_item.`" . $key . "` = '" . htmlspecialchars($value) . "',";
			}
				elseif ($key == "item_id")
				{
					$item_id = $value;
				}
		}
		$string = trim($string, ",");
		$query = "UPDATE podcast_item SET $string WHERE podcast_item.item_id = $item_id";
		$db->query($query);
	}
	
	function getItems($channel_id)
	{
		global $db;
		$i = "SELECT `pubDate`,
				 `title`,
				 `description`,
				 `filename`,
				 `length`,
				 `itunes:author`,
				 `itunes:category`,
				 `itunes:explicit`,
				 `itunes:subtitle`,
				 `itunes:summary`,
				 `itunes:duration`,
				 `itunes:keywords`
				 FROM podcast_item
				 WHERE `channel_id` = '$channel_id'";
		return ($db->get_results($i, ARRAY_A));
	}
	
	function getChannel($channel_id)
	{
		global $db;
		$j = "SELECT podcast_channel.title, 
					 podcast_channel.link, 
					 podcast_channel.description, 
					 podcast_channel.language, 
					 podcast_channel.managingEditor, 
					 podcast_channel.webMaster, 
					 podcast_channel.copyright, 
					 podcast_channel.`itunes:author`, 
					 podcast_channel.`itunes:subtitle`, 
					 podcast_channel.`itunes:summary`, 
					 podcast_channel.`itunes:name`, 
					 podcast_channel.`itunes:image`, 
					 podcast_channel.`itunes:category`
				FROM podcast_channel
				WHERE podcast_channel.channel_id = '$channel_id'";
		return ($db->get_row($j));
	}
	
	function getChannelDetails ($channel_id)
	{
		//print $channel_id;
		global $db;
		$k = "SELECT podcast_channel.channel_id, 
					 podcast_channel.title, 
					 podcast_channel.link, 
					 podcast_channel.description, 
					 podcast_channel.language, 
					 podcast_channel.managingEditor, 
					 podcast_channel.webMaster, 
					 podcast_channel.copyright,
					 podcast_channel.`itunes:author`, 
					 podcast_channel.`itunes:subtitle`, 
					 podcast_channel.`itunes:summary`, 
					 podcast_channel.`itunes:name`, 
					 podcast_channel.`itunes:image`, 
					 podcast_channel.`itunes:category`
				FROM podcast_channel
			   WHERE podcast_channel.channel_id = '$channel_id'";
		return($db->get_row($k, ARRAY_N));
	}
	
	function updateChannel ($data)
	{
		global $db;
		$channel_id = array_shift($data);
		foreach ($data as $key=>$value)
		{
			if ($key != "action")
			{
				$string .= "podcast_channel.`" . $key . "` = '" . htmlspecialchars($value) . "',";
			}
		}
		$string = trim($string, ",");
		//print $string;
		$query = "UPDATE podcast_channel SET $string WHERE podcast_channel.channel_id = $channel_id";
		$db->query($query);
	}
	
	function formatItems($channel_id)
	{
		$items = $this->getItems($channel_id);
		//print_r($items);
		$count = 0;
		foreach ( $items as $pod_fields )
		{
			foreach ($pod_fields as $key=>$value)
			{
				if ($key != "filename" && $key != "length" && $key != "itunes:category" && $value != "")
				{
					$pod[$count][$key] = "\t\t<" . $key . ">" . $value . "</" . $key . ">\n";
				}
			}
			
			$size = filesize($this->p_media . $pod_fields['filename']);
			
			$pod[$count]["link"] = "\t\t<link>" . $this->url . str_replace("../","",$this->p_media) . $pod_fields['filename'] . "</link>\n";
			$pod[$count]["enclosure"] = "\t\t<enclosure url=\"" . $this->url . str_replace("../","",$this->p_media) . $pod_fields['filename'] . "\" length=\"" . $size . "\" type=\"" . $this->type . "\"/>\n";
			$pod[$count]["category"] = "\t\t<category>Podcasts</category>\n";
			$pod[$count]["itunes:category"] = "\t\t<itunes:category text=\"Education\"/>\n\t\t<itunes:category text=\"International\">\n\t\t\t<itunes:category text=\"Japanese\"/>\n\t\t</itunes:category>\n";
			$pod[$count]["guid"] = "\t\t<guid>" . $this->url . str_replace("../","",$this->p_media) . $count . "_" . $pod_fields['filename'] . "</guid>\n";
			$pod[$count]["item_open"] = "\t\t<item>\n";
			$pod[$count]["item_close"] = "\t\t</item>\n\n";
			$count++;
			
			"\t\t<itunes:category text=\"Japanese\"/>\n\t\t<itunes:category text=\"International\">\n\t\t\t<itunes:category text=\"Japanese\"/>\n\t\t</itunes:category>\n";
		}
		
		foreach ($pod as $new_pod)
		{
			$pod_string .= $new_pod["item_open"];
			$pod_string .= $new_pod["title"];
			$pod_string .= $new_pod["link"];
			$pod_string .= $new_pod["description"];
			$pod_string .= $new_pod["enclosure"];
			$pod_string .= $new_pod["category"];
			$pod_string .= $new_pod["pubDate"];
			$pod_string .= $new_pod["guid"];
			$pod_string .= $new_pod["itunes:author"];
			$pod_string .= $new_pod["itunes:category"];
			$pod_string .= $new_pod["itunes:subtitle"];
			$pod_string .= $new_pod["itunes:summary"];
			$pod_string .= $new_pod["itunes:duration"];
			$pod_string .= $new_pod["itunes:keywords"];
			$pod_string .= $new_pod["item_close"];
		}
		//print $pod_string;
		return($pod_string);
	}
	
	function formatChannel($channel_id)
	{
		$chan = $this->getChannel($channel_id);
		foreach($chan as $key=>$value)
		{
			if ($key != "itunes:image" && $key != "itunes:category")
			{
				if ($value != "")
				{
					$channel[$key] = "\t\t<" . $key . ">" . $value . "</" . $key . ">\n";
				}
				if ($key == "webMaster")
				{
					$email = $value;
				}
				if ($key == "title")
				{
					$text = $value;
				}
			}
				elseif ($key == "itunes:image")
				{
					$image = $value;
				}
		}
		
		$channel_string .= $channel["title"];
		$channel_string .= $channel["link"];
		$channel_string .= $channel["description"];
		$channel_string .= $channel["language"];
		$channel_string .= $channel["managingEditor"];
		$channel_string .= $channel["webMaster"];
		$channel_string .= "\t\t" . "<pubDate>" . date("D, d M Y G:i:s O") . "</pubDate>\n";
		$channel_string .= "\t\t" . "<lastBuildDate>" . date("D, d M Y G:i:s O") . "</lastBuildDate>\n";
		$channel_string .= $channel["itunes:author"];
		$channel_string .= $channel["itunes:subtitle"];
		$channel_string .= $channel["itunes:summary"];
		$channel_string .= "\t\t<itunes:owner>\n\t" . $channel["itunes:name"] . "\t\t\t<itunes:email>" . $email . "</itunes:email>\n\t\t</itunes:owner>\n";
		$channel_string .= "\t\t<itunes:image rel=\"image\" href=\"" . $this->url . $this->img_dir . $image . "\">" . $text . "</itunes:image>\n";
		$channel_string .= "\t\t<itunes:category text=\"Education\"/>\n\t\t<itunes:category text=\"International\">\n\t\t\t<itunes:category text=\"Japanese\"/>\n\t\t</itunes:category>\n\n";
		return($channel_string);
	}
	
	function writePodcast($channel_id)
	{
		$pod_string = $this->formatItems($channel_id);
		$channel_string = $this->formatChannel($channel_id);
		$complete_string = $this->xml . $this->rss_open . $this->channel_open . $channel_string . $pod_string . $this->channel_close . $this->rss_close;
		if ($channel_id == "1")
		{
			$podcast = "../podcast.xml";
		}
			elseif ($channel_id == "2")
			{
				$podcast = "../podcast.xml";
			}
		
		if (is_writable($podcast)) 
		{
			if (!$handle = fopen($podcast, 'w+')) 
			{
				echo "Cannot open file ($podcast)";
				exit;
			}
			if (fwrite($handle, $complete_string) === FALSE) 
			{
				echo "Cannot write to file ($podcast)";
				exit;
			}
			fclose($handle);
		}
	}
	
	function categorySelect($i)
	{
		$array = array("Arts & Entertainment","Audio Blogs","Business","Comedy","Education","Family","Food","Health","International","Movies & Television","Music","News","Politics","Public Radio","Religion & Spirituality","Science","Sports","Talk Radio","Technology","Transportation","Travel");
		$string .= "\n\t\t<select name=\"itunes:category\">\n";
		foreach ($array as $value)
		{
			if ($value == $i)
			{
				$string .= "\t\t\t<option value=\"" . $value . "\" selected>" . $value . "</option>\n";
			}
				else
				{
					$string .= "\t\t\t<option value=\"" . $value . "\">" . $value . "</option>\n";
				}
		}
		$string .= "\t\t</select>\n\t";
		print $string;
	}
	
}

?>
