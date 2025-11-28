<?

class pagination
{
	function entries($email, $column, $order, $year, $month, $day, $year_end, $month_end, $day_end, $q_string)
	{
		print "\t\t<form name=\"pageentries\" method=\"get\" style=\"\">\n";
		print "\t\t\t<input type=\"hidden\" name=\"" . $q_string . "\"/>\n";
		print "\t\t\t<input type=\"hidden\" name=\"email\" value=\"" . $email . "\"/>\n";
		print "\t\t\t<input type=\"hidden\" name=\"year\" value=\"" . $year . "\"/>\n";
		print "\t\t\t<input type=\"hidden\" name=\"month\" value=\"" . $month . "\"/>\n";
		print "\t\t\t<input type=\"hidden\" name=\"day\" value=\"" . $day . "\"/>\n";
		print "\t\t\t<input type=\"hidden\" name=\"year_end\" value=\"" . $year_end . "\"/>\n";
		print "\t\t\t<input type=\"hidden\" name=\"month_end\" value=\"" . $month_end . "\"/>\n";
		print "\t\t\t<input type=\"hidden\" name=\"day_end\" value=\"" . $day_end . "\"/>\n";		
		print "\t\t\tEntries: \n";
		print "\t\t\t<select name=\"entries\" onChange=\"this.form.submit()\">\n";
		print "\t\t\t\t<option value=\"10\">10 per page\n";
		
		if ($_GET['entries'] == "20")
		{
			print "\t\t\t\t<option value=\"20\" selected=\"selected\">20 per page\n";
		}
			else 
			{
				print "\t\t\t\t<option value=\"20\">20 per page\n";
			}
		if ($_GET['entries'] == "30")
		
		{
			print "\t\t\t\t<option value=\"30\" selected=\"selected\">30 per page\n";
		}
			else 
			{
				print "\t\t\t\t<option value=\"30\">30 per page\n";
			}
		
		print "\t\t\t</select>\n";
		print "\t\t\t<input type=\"hidden\" name=\"column\" value=\"" . $column . "\"/>\n";
		print "\t\t\t<input type=\"hidden\" name=\"order\" value=\"" . $order . "\"/>\n";
		print "\t\t</form>\n\n";
	}
	
	function pages($email, $entries, $column, $order, $total, $year, $month, $day, $year_end, $month_end, $day_end, $q_string)
	{
		if (!isset($_GET['entries']) || $_GET['entries'] == '')
		{
			$entries = 10;
		}
			else
			{
				$entries = $_GET['entries'];
			}
						
		if (!isset($_GET['pageindex']))
		{
			$pageindex = 0;
		}
			else
			{
				$pageindex = $_GET['pageindex'];	
			}
	
		if ($total > $entries)
		{
			$pages = ceil($total / $entries);
			
			if (isset($_GET['pg']))
			{
				$pg = $_GET['pg'];
			}
				else
				{
					$pg = 1;
				}
				
			$low = $pg - 3;
			
			if ($low < 1)
			{
				$low = 0;
			}
		
			$hi = $pg + 2;
			
			if ($hi > $pages)
			{
				$hi = $pages;
			}
			
			print "<div style=\"padding-bottom: 24px\">";
			print "<table cellspacing=\"1\" align=\"right\" style=\"padding: 0px; border-style: solid; border-width: 1px; border-color: #8C1414; background: #cccccc\">";
			print "<tr>";
			
			if (!isset($_GET['pg']))
			{
				print "<td style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #ffffff; background: #777\">";
				print "\t\t&nbsp;Page 1 of " . $pages . "&nbsp;\n";
				print "</td>";
			}
				else
				{
					print "<td style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #ffffff; background: #777\">";
					print "\t\t&nbsp;Page " . $_GET['pg'] . " of " . $pages . "&nbsp;\n";
					print "</td>";
				}
			
			if ($_GET['pg'] > 3)
			{
			
			
				print "<td style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; background: #ffffff\">";
				print "\t\t&nbsp;<a href=\"?" . $q_string . "&email=" . $email . "&year=" . $year . "&month" . $month . "&day" . $day ."&year_end=" . $year_end . "&month_end" . $month_end . "&day_end" . $day_end . "&column=" . $column . "&order=" . $order . "&entries=" . $entries . "&pageindex=0&pg=1\"class=\"pagelink\">";
				print "&#171; First";
				print "</a>" . "&nbsp;\n";
				print "</td>";
			}
				
			if ($_GET['pg'] > 1)
			{
				$page = $_GET['pg'] -1;
				$malibu = $pageindex - $entries;
				print "<td style=\"padding: 0px 2px 0px 2px; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; background: #ffffff\">";
				print "\t\t<a href=\"?" . $q_string . "&email=" . $email . "&year=" . $year . "&month=" . $month . "&day=" . $day ."&year_end=" . $year_end . "&month_end=" . $month_end . "&day_end=" . $day_end . "&column=" . $column . "&order=" . $order . "&entries=" . $entries . "&pageindex=" . $malibu . "&pg=" . $page . "\"class=\"pagelink\">";
				print "<";
				print "</a>" . "\n";
				print "</td>";
			}
			
			for ($i = $low; $i < $hi; $i++)
			{
				$page = $i+1;
		
				if ($page == $pg)
				{
					print "<td style=\"padding: 0px 2px 0px 2px; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; background: #eeeeee\">";
					print $i + 1;
					print "</td>";
				}
					else
					{
						print "<td style=\"padding: 0px 2px 0px 2px; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; background: #ffffff\">";
						print "\t\t<a href=\"?" . $q_string . "&email=" . $email . "&year=" . $year . "&month=" . $month . "&day=" . $day ."&year_end=" . $year_end . "&month_end=" . $month_end . "&day_end=" . $day_end . "&column=" . $column . "&order=" . $order . "&entries=" . $entries . "&pageindex=" . $i * $entries . "&pg=" . $page . "\"class=\"pagelink\">";
						print $i + 1;
						print "</a>" . "\n";
						print "</td>";
					}
			}
			
			if ($_GET['pg'] < $pages)
			{
				if(!isset($_GET['pg']))
				{
					$page = 2;
				}
					else
					{
						$page = $_GET['pg'] +1;
					}
				
				$malibu = $pageindex + $entries;
				print "<td style=\"padding: 0px 2px 0px 2px; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; background: #ffffff\">";
				print "\t\t<a href=\"?" . $q_string . "&email=" . $email . "&year=" . $year . "&month=" . $month . "&day=" . $day ."&year_end=" . $year_end . "&month_end=" . $month_end . "&day_end=" . $day_end . "&column=" . $column . "&order=" . $order . "&entries=" . $entries . "&pageindex=" . $malibu . "&pg=" . $page . "\"class=\"pagelink\">";
				print ">";
				print "</a>" . "\n";
				print "</td>";
								
				$last = ($pages * $entries) - $entries;
				print "<td style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; background: #ffffff\">";
				print "\t\t<a href=\"?" . $q_string . "&email=" . $email . "&year=" . $year . "&month=" . $month . "&day=" . $day ."&year_end=" . $year_end . "&month_end=" . $month_end . "&day_end=" . $day_end . "&column=" . $column . "&order=" . $order . "&entries=" . $entries . "&pageindex=" . $last . "&pg=" . $pages . "\"class=\"pagelink\">";
				print "&nbsp;Last &#187";
				print "</a>" . "&nbsp;\n";
				print "</td>";
			}
			print "</tr>";
			print "</table>";
			print "</div>";
			
			
		}
	}
}

//pagination(21);
?>