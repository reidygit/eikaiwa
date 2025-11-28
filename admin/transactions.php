<?

include_once("../php/ez_sql.php");
include_once("../php/pagination_class.php");
$page = new pagination();

if (empty($_GET["column"]) && empty($_GET["order"]))
{
	$sort = "paypal_subscriptions.date ASC, paypal_subscriptions.subscr_id DESC";
}
	else
	{
		$sort = $_GET["column"] . " " . $_GET["order"];
	}
	
if (empty($entries))
{
	$entries = "10";
}

if (empty($pageindex))
{
	$pageindex = "0";
}

global $db;
$query = "SELECT DATE_FORMAT(paypal_subscriptions.date, '%Y-%m-%d') as date, 
	paypal_subscriptions.txn_type, 
	users.first_name, 
	users.last_name,
	users.email,
	paypal_subscriptions.item_name, 
	paypal_subscriptions.subscr_id, 
	paypal_subscriptions.mc_gross
FROM paypal_subscriptions, users, users_subscriptions
WHERE users.user_id = users_subscriptions.user_id AND users_subscriptions.subscr_id = paypal_subscriptions.subscr_id
ORDER BY $sort
LIMIT $pageindex, $entries";

$t_query = "SELECT count(paypal_subscriptions.id) as total FROM paypal_subscriptions";

$total = $db->get_var($t_query);

//print $query;
$results = $db->get_results($query);

$count = 0;
//print_r($results);

function sort_link($column)
{
	$up = "../img/sort_up.gif";
	$down = "../img/sort_down.gif";
	print "<a href=\"?transaction_list&email=$email&year=$year&month=$month&day=$day&year_end=$year_end&month_end=$month_end&day_end=$day_end&entries=$entries&column=$column&order=DESC\"><img src=\"$down\" border=\"0\" alt=\"\" style=\"padding-bottom: 1px;\"></a>&nbsp;<a href=\"?transaction_list&email=$email&year=$year&month=$month&day=$day&year_end=$year_end&month_end=$month_end&day_end=$day_end&entries=$entries&column=$column&order=ASC\"><img src=\"$up\" border=\"0\" alt=\"\" style=\"padding-bottom: 1px;\"></a>";
}

?>

<table cellpadding="0" cellspacing="0" style="font-family: arial, helvetica, sans-serif; font-size: 11px;">
</tr>
	<td style="padding-top: 6px; text-align: left;"><?php $page->entries($email, $column, $order, $year, $month, $day, $year_end, $month_end, $day_end, "transaction_list"); ?></td>
	<td style="text-align: right;"><?php $page->pages($email, $entries, $column, $order, $total, $year, $month, $day, $year_end, $month_end, $day_end, "transaction_list"); ?></td>
</tr>
<tr>
	<td colspan="2">
		<table cellpadding="0" cellspacing="0" style="font-family: arial, helvetica, sans-serif; font-size: 11px; border-style: solid; border-color: #8C1414; border-width: 1px;">
		<tr>
			<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Date <? sort_link("date"); ?></td>
			<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Transaction Type <? sort_link("txn_type"); ?></td>
			<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">First Name <? sort_link("first_name"); ?></td>
			<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Last Name <? sort_link("last_name"); ?></td>
			<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Email <? sort_link("email"); ?></td>
			<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Subscription Type <? sort_link("txn_type"); ?></td>
			<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Subscription ID <? sort_link("subscr_id"); ?></td>
			<td style="padding: 2px 8px 2px 8px; border-bottom-style: solid; border-bottom-color: #8C1414; border-bottom-width: 1px; font-weight: bold;">Amount <? sort_link("mc_gross"); ?></td>
		</tr>
		<?
		foreach ($results as $result)
		{
		$class = ( $count & 1 ) ? 'padding: 4px 8px 4px 8px; background: #ccc;' : 'padding: 4px 8px 4px 8px;';
		?>
		<tr>
		<?
			foreach($result as $key=>$value)
			{	
				
				if ($value == "PREMIUM nihongo.fm Monthly Subscription")
					{
						?><td style="<? print $class; ?>">Monthly Subscription</td><?
					}
						elseif ($value == "PREMIUM nihongo.fm Yearly Subscription")
						{
							?><td style="<? print $class; ?>">Annual Subscription</td><?
						}
							elseif ($key == "email")
							{
								?><td style="<? print $class; ?>"><a href="?edituser&email=<? print $value; ?>"><? print $value; ?></a></td><?
							}
								else
								{
									?><td style="<? print $class; ?>"><? print $value; ?></td><?
								}
				
			}
		?>
		</tr>
		<?
		$count++;
		}
		?>
		</table>
	</td>
</tr>
</table>